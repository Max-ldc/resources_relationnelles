import './ConfirmationModal.css';

function ConfirmationModal({ isOpen, onClose, onConfirm, title, message }) {
    if (!isOpen) return null;

    return (
        <div className="modal-backdrop">
            <div className="modal">
                <h2>{title}</h2>
                <p>{message}</p>
                <button onClick={onConfirm}>Confirmer</button>
                <button onClick={onClose}>Annuler</button>
            </div>
        </div>
    );
}

export default ConfirmationModal;