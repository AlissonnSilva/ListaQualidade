describe("Pedidos e fretes", () => {
  it("cria cliente, produto, pedido e frete e confirma a baixa de estoque", () => {
    cy.fixture("cliente.json").then((cliente) => {
      cy.fixture("produto.json").then((produto) => {
        const customerName = `${cliente.name} pedido completo`;
        const productName = `${produto.name} pedido completo`;
        cy.criarCliente({ ...cliente, name: customerName });
        cy.criarProduto({ ...produto, name: productName, stock: 20 });

        cy.visit("/produtos");
        cy.contains('[data-cy="product-row"]', productName).find('[data-cy="product-stock"]')
          .should("contain", "20");

        cy.visit("/pedidos/create");
        cy.get('[data-cy="order-customer"]').select(customerName);
        cy.get('[data-cy="order-product"] option').contains(productName).invoke("val")
          .then((productId) => cy.get('[data-cy="order-product"]').select(productId));
        cy.get('[data-cy="order-quantity"]').clear().type("3");
        cy.get('[data-cy="order-status"]').select("confirmed");
        cy.intercept("POST", "/pedidos").as("createOrder");
        cy.get('[data-cy="order-submit"]').click();
        cy.wait("@createOrder").its("response.statusCode").should("be.oneOf", [302, 303]);
        cy.get('[data-cy="flash-success"]').should("contain", "estoque atualizado");
        cy.contains('[data-cy="order-row"]', customerName).should("contain", "Confirmado");

        cy.visit("/produtos");
        cy.contains('[data-cy="product-row"]', productName).find('[data-cy="product-stock"]')
          .should("contain", "17");

        cy.visit("/entregas/create");
        cy.get('[data-cy="delivery-order"]').select("1");
        cy.get('[data-cy="delivery-address"]').clear().type(cliente.address);
        cy.get('[data-cy="delivery-city"]').clear().type(cliente.city);
        cy.get('[data-cy="delivery-fee"]').clear().type("12.50");
        cy.get('[data-cy="delivery-scheduled-at"]').type("2026-12-01");
        cy.get('[data-cy="delivery-tracking-code"]').type("CYPRESS-001");
        cy.get('[data-cy="delivery-submit"]').click();
        cy.contains('[data-cy="delivery-row"]', "CYPRESS-001").should("contain", "Agendada");

        cy.contains('[data-cy="delivery-row"]', "CYPRESS-001").find('[data-cy="edit-delivery"]').click();
        cy.get('[data-cy="delivery-status"]').select("in_transit");
        cy.get('[data-cy="delivery-submit"]').click();
        cy.contains('[data-cy="delivery-row"]', "CYPRESS-001").should("contain", "Em trânsito");

        cy.contains('[data-cy="delivery-row"]', "CYPRESS-001").find('[data-cy="edit-delivery"]').click();
        cy.get('[data-cy="delivery-status"]').select("delivered");
        cy.get('[data-cy="delivery-submit"]').click();
        cy.contains('[data-cy="delivery-row"]', "CYPRESS-001").should("contain", "Entregue");
      });
    });
  });

  it("impede um pedido com quantidade maior que o estoque", () => {
    cy.fixture("cliente.json").then((cliente) => {
      cy.fixture("produto.json").then((produto) => {
        const customerName = `${cliente.name} sem estoque`;
        const productName = `${produto.name} estoque curto`;
        cy.criarCliente({ ...cliente, name: customerName });
        cy.criarProduto({ ...produto, name: productName, stock: 2 });
        cy.visit("/pedidos/create");
        cy.get('[data-cy="order-customer"]').select(customerName);
        cy.get('[data-cy="order-product"] option').contains(productName).invoke("val")
          .then((productId) => cy.get('[data-cy="order-product"]').select(productId));
        cy.get('[data-cy="order-quantity"]').clear().type("3");
        cy.get('[data-cy="order-submit"]').click();
        cy.get('[data-cy="error-quantity"]').should("be.visible");
        cy.visit("/pedidos");
        cy.contains('[data-cy="order-row"]', customerName).should("not.exist");
        cy.visit("/produtos");
        cy.contains('[data-cy="product-row"]', productName).find('[data-cy="product-stock"]')
          .should("contain", "2");
      });
    });
  });
});
