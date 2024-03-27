// test creer-compte client
describe('template spec', () => {
    it('passes', () => {
      cy.visit('http://localhost:3000/')
      cy.contains('Créer un compte').click()
  
      cy.get('input[type="text"]').type('123')
      cy.get('input[type="email"]').type('123@gmail.com')
      cy.get('input[name="password"]').type('pass1')
      cy.get('input[name="password_verif"]').type('pass1')

      cy.contains('Valider').click()
    })
  })

  describe('template error', () => {
    it('passes', () => {
      cy.visit('http://localhost:3000/')
      cy.contains('Créer un compte').click()
  
      cy.get('input[type="text"]').type('123')
      cy.get('input[type="email"]').type('123@gmail')
      cy.get('input[name="password"]').type('pass1')
      cy.get('input[name="password_verif"]').type('pass1')

      cy.contains('Valider').click()
    })
  })