
describe('Simule le clic sur un bouton "Voir" d’une ressource et vérifie la navigation', () => {
  it('devrait permettre accéder de la ressource', () => {
      cy.visit('http://localhost:3000/')
      cy.get('article:first').should('have.class', 'video')
      cy.contains('Voir').click()
    })
  })
  
  