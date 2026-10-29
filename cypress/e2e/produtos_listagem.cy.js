describe("Listagem de produtos", () => {
  beforeEach(() => {
    cy.visit("/produtos");
  });

  it("exibe tabela, dados e os cabeçalhos principais", () => {
    cy.get('[data-cy="products-table"]').should("be.visible");
    cy.get('[data-cy="products-table"] tbody tr').should("have.length.at.least", 1);
    cy.get('[data-cy="products-table"] th').should("contain", "Produto");
    cy.get('[data-cy="products-table"] th').should("contain", "Categoria");
    cy.get('[data-cy="products-table"] th').should("contain", "Preço");
    cy.get('[data-cy="products-table"] th').should("contain", "Estoque");
  });
});
