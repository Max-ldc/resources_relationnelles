import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';

const ResourcePage = () => {
    let { resourceId } = useParams();
    const [pdfFileUrl, setPdfFileUrl] = useState('');

    useEffect(() => {
        // TODO : utiliser un ID dynamique
        const url = `http://localhost/api/resources/1/read`;
        setPdfFileUrl(url);
    }, [resourceId]);

    if (!pdfFileUrl) return <div>Loading...</div>;

    return (
        <div>
            {/* Temporary title */}
            <h1>Lecture de la resource</h1>
            <iframe
                src={pdfFileUrl}
                style={{width: '100%', height: '80vh'}}
                title="PDF Viewer"
            ></iframe>
        </div>
    );
};

export default ResourcePage;