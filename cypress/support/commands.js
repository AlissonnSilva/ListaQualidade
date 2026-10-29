Cypress.Commands.add("criarProduto", (dados) => {
  cy.visit("/produtos/create");
  cy.get('[data-cy="product-name"]').clear().type(dados.name);
  cy.get('[data-cy="product-category"]').select(dados.category);
  cy.get('[data-cy="product-description"]').clear().type(dados.description ?? "Produto criado pela suíte Cypress.");
  cy.get('[data-cy="product-price"]').clear().type(String(dados.price));
  cy.get('[data-cy="product-stock"]').clear().type(String(dados.stock));
  cy.get('[data-cy="product-minimum-stock"]').clear().type(String(dados.minimum_stock));
  if (dados.active === false) cy.get('[data-cy="product-active"]').uncheck();
  cy.get('[data-cy="product-submit"]').click();
  cy.location("pathname").should("eq", "/produtos");
  cy.get('[data-cy="flash-success"]').should("be.visible");
});

Cypress.Commands.add("criarCliente", (dados) => {
  cy.visit("/clientes/create");
  cy.get('[data-cy="customer-name"]').clear().type(dados.name);
  if (dados.email) cy.get('[data-cy="customer-email"]').clear().type(dados.email);
  if (dados.phone) cy.get('[data-cy="customer-phone"]').clear().type(dados.phone);
  if (dados.address) cy.get('[data-cy="customer-address"]').clear().type(dados.address);
  if (dados.city) cy.get('[data-cy="customer-city"]').clear().type(dados.city);
  cy.get('[data-cy="customer-submit"]').click();
  cy.location("pathname").should("eq", "/clientes");
  cy.get('[data-cy="flash-success"]').should("be.visible");
});
