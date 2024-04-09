import React, { useState, useEffect } from "react";
import axios from "axios";
import {
    Button,
    Cell,
    Column,
    Row,
    Table,
    TableBody,
    TableHeader,
} from "react-aria-components";
import "./ressource.css";
const Resource = () => {
    const [resources, setResources] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchResources();
    }, []);

    const fetchResources = () => {
        setLoading(true);
        axios
            .get("http://localhost/api/resources")
            .then((response) => {
                setResources(response.data["hydra:member"]);
                setLoading(false);
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
                setLoading(false);
            });
    };

    return (
        <div className="container_resource">
            <h1 className="page-title">Liste des Ressources</h1>
            {loading ? (
                <p>Chargement...</p>
            ) : (
                <Table className="table" aria-label="Ressources">
                    <TableHeader>
                        <Column isRowHeader={true}>Nom du Fichier</Column>
                        <Column>Status Partagé</Column>
                        <Column>Utilisateur</Column>
                        <Column>Titre</Column>
                        <Column>Auteur</Column>
                        <Column>Catégorie</Column>
                        <Column>Type</Column>
                    </TableHeader>
                    <TableBody className="tableBody">
                        {resources.map((resource, index) => (
                            <Row className="row" key={index}>
                                <Cell>{resource.fileName}</Cell>
                                <Cell>{resource.sharedStatus}</Cell>
                                <Cell>{resource.userData.user.username}</Cell>
                                <Cell>{resource.resourceMetadata.title}</Cell>
                                <Cell>{resource.resourceMetadata.author}</Cell>
                                <Cell>{resource.category}</Cell>
                                <Cell>{resource.type}</Cell>
                            </Row>
                        ))}
                    </TableBody>
                </Table>
            )}
        </div>
    );
};

export default Resource;
