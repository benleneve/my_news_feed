import {render, unmountComponentAtNode} from 'react-dom';
import React, {useEffect, useState} from 'react';
import {usePaginatedFetch} from "./hooks";
import {Modal} from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';


const dateFormat = {
    dateStyle: 'medium',
    timeStyle: 'short'
}

function NewsList () {
    const {items: news, load, loading, count, hasMore} = usePaginatedFetch('/api/news');

    useEffect(() => {
        load()
    }, []);

    return <div className="container">
        <h2>
            <span>My News Feed</span>
            Created with <i className="fa fa-heart"></i> by benleneve
        </h2>
        <h3>
            <i className="fa fa-file-text" aria-hidden="true"></i> {count} New{count > 1 ? 's' : ''}
        </h3>
        <div className="row-section">
            {news.map(newItem => <NewsItem key={newItem.id} newItem={newItem}/>)}
        </div>
        <div className="btn-section">
            {hasMore && <button disabled={loading} className="btn btn-primary" onClick={load}>Load more news</button>}
        </div>

    </div>;
}

const NewsItem = React.memo(({newItem}) => {

    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);
    const date = new Date(newItem.publishedAt);

    return <div className="row-block">
        <div className="row-block-image">
            <img src={newItem.imageUrl}/>
        </div>
        <div className="row-block-content">
            <h3>{newItem.title}</h3>
            <p>{newItem.description}</p>
        </div>
        <div className="row-block-btn">
            <button className="btn btn-secondary" onClick={handleShow}>See more</button>
        </div>
        <Modal show={show} onHide={handleClose} size="xl">
            <Modal.Header closeButton>
                <Modal.Title>
                    {newItem.title}
                </Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <div className="modal-content">
                    <img src={newItem.imageUrl}/>
                    <p>Written by {newItem.author} -- Published at {date.toLocaleString(undefined, dateFormat)}</p>
                    <p>{newItem.content}</p>
                    <a href={newItem.url} target="_blank">See full article</a>
                </div>
            </Modal.Body>
        </Modal>
    </div>;
});

class HomeElement extends HTMLElement {

    connectedCallback () {
        render(<NewsList/>, this);
    }

    disconnectedCallback () {
        unmountComponentAtNode(this);
    }
}

customElements.define('home-news', HomeElement);