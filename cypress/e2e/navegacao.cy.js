describe("Navegação pelo menu", () => {
  beforeEach(() => {
    cy.visit("/");
  });

  it("abre Produtos, Clientes, Pedidos e Fretes", () => {
    cy.get('[data-cy="nav-products"]').click();
    cy.url().should("include", "/produtos");
    cy.get('[data-cy="nav-customers"]').click();
    cy.url().should("include", "/clientes");
    cy.get('[data-cy="nav-orders"]').click();
    cy.url().should("include", "/pedidos");
    cy.get('[data-cy="nav-deliveries"]').click();
    cy.url().should("include", "/entregas");
  });
});
