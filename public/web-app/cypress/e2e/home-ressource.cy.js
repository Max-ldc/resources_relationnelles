//test ressources home page
describe('template spec', () => {
    it('passes', () => {
      cy.visit('http://localhost:3000/')
      cy.get('.video:first')
      // cy.get('article:first').should('have.class', 'video')
      cy.contains('Voir').click()
    })
  })
  
  