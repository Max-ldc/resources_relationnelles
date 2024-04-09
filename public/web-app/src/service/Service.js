import React, { useState, useEffect } from "react";
import axios from "axios";
import { Link } from "react-router-dom";
import ConfirmationModal from "../component/ConfirmationModal";
import CreateUserModal from "../component/CreateUserModal";

import {
    Button,
    Cell,
    Column,
    Row,
    Table,
    TableBody,
    TableHeader,
} from "react-aria-components";
import "./service.css";
import MyCheckbox from "./MyCheckbox";
// https://levelup.gitconnected.com/how-to-make-an-api-call-with-all-crud-operations-in-react-ed6e6b94c363

const UserRoleEnum = {
    USER_ROLE_MODERATOR: "modérateur",
    USER_ROLE_CATALOG_ADMIN: "administrateur",
    USER_ROLE_SUPER_ADMIN: "super administrateur",
};

const Service = () => {
    const [users, setAllUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [userIdToDelete, setUserIdToDelete] = useState(null);
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    const [modalTitle, setModalTitle] = useState("");
    const [modalMessage, setModalMessage] = useState("");
    const [userToModify, setUserToModify] = useState(null);
    const [modalKey, setModalKey] = useState(0);
    const [modalAction, setModalAction] = useState(null);

    const askChangeAccountStatus = (user) => {
        setUserToModify(user);
        setModalTitle(user.accountEnabled ? "Désactiver le compte ?" : "Activer le compte ?");
        setModalMessage(`Êtes-vous sûr de vouloir ${user.accountEnabled ? "désactiver" : "activer"} le compte de ${user.username} ?`);
        setModalAction("changeStatus");
        setIsModalOpen(true);
    };

    const askDeleteUser = (userId) => {
        setUserIdToDelete(userId);
        setModalTitle("Supprimer l'utilisateur");
        setModalMessage("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");
        setModalAction("delete");
        setIsModalOpen(true);
    };

    useEffect(() => {
        fetchUsers();
    }, []);

    const fetchUsers = () => {
        setLoading(true);
        axios
            .get("http://localhost/api/users")
            .then((response) => {
                setAllUsers(response.data);
                setLoading(false);
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
                setLoading(false);
            });
    };

    const deleteUser = (userId) => {
        axios
            .delete(`http://localhost/api/users/${userId}`)
            .then(() => {
                const updatedUsers = users["hydra:member"].filter(
                    (user) => user.id !== userId,
                );
                setAllUsers({ ...users, "hydra:member": updatedUsers });
            })
            .catch((error) => {
                console.error("Error deleting user:", error);
            });
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setModalKey(prevKey => prevKey + 1);
    };

    const handleConfirmDelete = () => {
        deleteUser(userIdToDelete);
        setIsModalOpen(false);
    };

    const openCreateUserModal = () => {
        setIsCreateModalOpen(true);
    };

    const handleCloseCreateModal = () => {
        setIsCreateModalOpen(false);
    };

    const handleCreateUser = (userData) => {
        axios
            .post("http://localhost/user", userData)
            .then((response) => {
                fetchUsers();
                setIsCreateModalOpen(false);
                setModalKey(prevKey => prevKey + 1);
            })
            .catch((error) => {
                console.error(
                    "Il y a eu une erreur lors de la création de l'utilisateur",
                    error,
                );
            });
    };

    const handleConfirmAction = () => {
        if (modalAction === "delete") {
            deleteUser(userIdToDelete);
        } else if (modalAction === "changeStatus" && userToModify) {
            handleConfirmChange();
        }
        setIsModalOpen(false);
    };

    const handleConfirmChange = () => {
        if (userToModify) {
            const newAccountEnabledStatus = !userToModify.accountEnabled;

            axios.patch(`http://localhost/api/users/${userToModify.id}`, {
                accountEnabled: newAccountEnabledStatus
            }, {
                headers: {
                    'Content-Type': 'application/merge-patch+json' // Spécifie le bon Content-Type
                }
            })
                .then(() => {
                    fetchUsers();
                    setIsModalOpen(false);
                })
                .catch((error) => {
                    console.error("Erreur lors de la mise à jour de l'utilisateur:", error);
                });
        }
    };

    return (
        <div className="container_listservice">
            <div className="container_createuser">
                <h1>Créer un utilisateur</h1>
                <Button onClick={openCreateUserModal} className="create-user-btn">
                    Créer un super utilisateur
                </Button>
            </div>
            <div className="container_userlist">
                <h1>Liste des utilisateurs</h1>
                {loading ? (
                    <p>Chargement...</p>
                ) : (
                    <>
                        <Table aria-label="Files" selectionMode="multiple">
                            <TableHeader>
                                <Column>
                                    <MyCheckbox slot="selection" />
                                </Column>
                                <Column isRowHeader>ID</Column>
                                <Column>Nom d'utilisateur</Column>
                                <Column>Rôle</Column>
                                <Column>Etat</Column>
                                <Column className="btn_cell"></Column>
                                <Column className="btn_cell"></Column>
                                <Column className="btn_cell"></Column>
                            </TableHeader>

                            <TableBody>
                                {users["hydra:member"]?.map((user) => (
                                    <Row key={user.id}>
                                        <Cell>
                                            <MyCheckbox slot="selection" />
                                        </Cell>
                                        <Cell>{user.id}</Cell>
                                        <Cell>{user.username}</Cell>
                                        <Cell>{user.role}</Cell>
                                        <Cell>{user.accountEnabled ? "Activé" : "Désactivé"}</Cell>
                                        <Cell className="btn_cell">
                                                <Button
                                                    className="btn_modif"
                                                    onClick={() => askChangeAccountStatus(user)}
                                                >
                                                    modifier
                                                </Button>
                                        </Cell>
                                        <Cell className="btn_cell">
                                            <Button
                                                className="btn_supprimer"
                                                onClick={() => askDeleteUser(user.id)}
                                            >
                                                supprimer
                                            </Button>
                                        </Cell>
                                        <Cell className="btn_cell">
                                            <Link to={`/user/${user.id}`}>
                                                <Button>détails</Button>
                                            </Link>
                                        </Cell>
                                    </Row>
                                ))}
                            </TableBody>
                        </Table>
                        <ConfirmationModal
                            isOpen={isModalOpen}
                            onClose={handleCloseModal}
                            onConfirm={handleConfirmAction}
                            title={modalTitle}
                            message={modalMessage}
                        />
                    </>
                )}
            </div>
            <CreateUserModal
                isOpen={isCreateModalOpen}
                onClose={handleCloseCreateModal}
                onSubmit={handleCreateUser}
                roles={UserRoleEnum}
            />
        </div>
    );
};

export default Service;
