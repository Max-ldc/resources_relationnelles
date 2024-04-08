describe('Simule le clic sur un bouton "Voir" d’une ressource et vérifie la navigation', () => {
  it("devrait permettre accéder de la ressource", () => {
    cy.visit("http://localhost:3000/");
    cy.get("article:first").should("have.class", "video");
    cy.contains("Voir").click();
  });
});

describe('Verifier le menu de navigation', () => {
  it("devrait permettre vérifier que les bouttons navigation existent", () => {
    cy.visit("http://localhost:3000/");

    cy.get('.header')
    cy.contains('Accueil')
    cy.contains('Ressources')
    cy.contains('Uploader')
    cy.contains('Admin')
    cy.contains('Se connecter');
  });
});
