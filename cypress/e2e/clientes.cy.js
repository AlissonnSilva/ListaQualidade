describe("Cadastro e manutenção de clientes", () => {
  it("cria, edita e exclui um cliente com contato e endereço", () => {
    cy.fixture("cliente.json").then((cliente) => {
      const name = `${cliente.name} CRUD`;
      cy.criarCliente({ ...cliente, name });
      cy.contains('[data-cy="customer-row"]', name)
        .should("contain", cliente.email)
        .and("contain", cliente.phone)
        .and("contain", cliente.address);

      cy.contains('[data-cy="customer-row"]', name).find('[data-cy="edit-customer"]').click();
      cy.get('[data-cy="customer-name"]').clear().type(`${name} atualizado`);
      cy.get('[data-cy="customer-submit"]').click();
      cy.contains('[data-cy="customer-row"]', `${name} atualizado`).should("be.visible");

      cy.on("window:confirm", () => true);
      cy.contains('[data-cy="customer-row"]', `${name} atualizado`)
        .find('[data-cy="delete-customer"]')
        .click();
      cy.contains('[data-cy="customer-row"]', `${name} atualizado`).should("not.exist");
    });
  });
});
