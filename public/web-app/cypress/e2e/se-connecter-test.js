
describe('Tests de connexion de l’utilisateur', () => {
  it('devrait permettre à un utilisateur de se connecter via le menu', () => {
    cy.visit('http://localhost:3000/')
    cy.get('.icon-menu-open').click() //via menu
    cy.contains('Se connecter').click()
    cy.get('input[type="text"]').type('123')
    cy.get('input[type="password"]').type('pass')
    cy.contains('Valider').click()
  })
})