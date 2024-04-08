

describe('Tests de créer un compte de utilisateur', () => {
  it('devrait permettre à un utilisateur de créer un compte via menu', () => {
      cy.visit('http://localhost:3000/')
      cy.contains('Créer un compte').click()
  
      cy.get('input[type="text"]').type('123')
      cy.get('input[type="email"]').type('123@gmail.com')
      cy.get('input[name="password"]').type('pass1')
      cy.get('input[name="password_verif"]').type('pass1')

      cy.contains('Valider').click()
    })
  })

describe('Tests de créer un compte utilisateur avec une erreur de validation', () => {
  it('devrait vérifier que si le client a fait une erreur, refus de validation avec un message d’erreur', () => {
    cy.visit('http://localhost:3000/')
    cy.contains('Créer un compte').click()

    cy.get('input[type="text"]').type('123')
    cy.get('input[type="email"]').type('123@gmail')
    cy.get('input[name="password"]').type('pass1')
    cy.get('input[name="password_verif"]').type('pass1')

    cy.contains('aaaa').click()
  })
})