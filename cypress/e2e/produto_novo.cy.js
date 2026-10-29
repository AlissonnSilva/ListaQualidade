describe("Cadastro de produtos", () => {
  beforeEach(() => {
    cy.visit("/produtos");
  });

  it("abre o formulário com os campos esperados", () => {
    cy.get('[data-cy="new-product"]').click();
    cy.location("pathname").should("eq", "/produtos/create");
    cy.get('[data-cy="product-name"]').should("be.visible");
    cy.get('[data-cy="product-category"]').should("be.visible");
    cy.get('[data-cy="product-price"]').should("be.visible");
    cy.get('[data-cy="product-stock"]').should("be.visible");
    cy.get('[data-cy="product-minimum-stock"]').should("be.visible");
  });

  it("cadastra um produto usando os dados da fixture", () => {
    cy.fixture("produto.json").then((produto) => {
      cy.criarProduto({ ...produto, name: `${produto.name} novo` });
      cy.contains('[data-cy="product-row"]', `${produto.name} novo`).should("be.visible");
    });
  });

  it("mostra mensagens nos campos obrigatórios quando o formulário está vazio", () => {
    cy.visit("/produtos/create");
    cy.get('[data-cy="product-form"] [required]').invoke("removeAttr", "required");
    cy.get('[data-cy="product-price"]').clear();
    cy.get('[data-cy="product-stock"]').clear();
    cy.get('[data-cy="product-minimum-stock"]').clear();
    cy.get('[data-cy="product-submit"]').click();
    cy.get('[data-cy="error-name"]').should("be.visible");
    cy.get('[data-cy="error-category"]').should("be.visible");
    cy.get('[data-cy="error-price"]').should("be.visible");
    cy.get('[data-cy="error-stock"]').should("be.visible");
    cy.get('[data-cy="error-minimum-stock"]').should("be.visible");
  });

  it("rejeita preço negativo", () => {
    cy.visit("/produtos/create");
    cy.get('[data-cy="product-price"]').invoke("removeAttr", "min").clear().type("-1");
    cy.get('[data-cy="product-form"]').submit();
    cy.get('[data-cy="error-price"]').should("be.visible");
  });

  it("rejeita estoque negativo", () => {
    cy.visit("/produtos/create");
    cy.get('[data-cy="product-stock"]').invoke("removeAttr", "min").clear().type("-1");
    cy.get('[data-cy="product-form"]').submit();
    cy.get('[data-cy="error-stock"]').should("be.visible");
  });

  it("rejeita texto em um campo numérico", () => {
    cy.visit("/produtos/create");
    cy.get('[data-cy="product-stock"]').invoke("attr", "type", "text").clear().type("abc");
    cy.get('[data-cy="product-form"]').submit();
    cy.get('[data-cy="error-stock"]').should("be.visible");
  });

  it("edita o preço de um produto existente", () => {
    cy.fixture("produto.json").then((produto) => {
      const name = `${produto.name} para editar`;
      cy.criarProduto({ ...produto, name });
      cy.contains('[data-cy="product-row"]', name).find('[data-cy="edit-product"]').click();
      cy.get('[data-cy="product-price"]').clear().type("49.90");
      cy.get('[data-cy="product-submit"]').click();
      cy.get('[data-cy="flash-success"]').should("contain", "atualizado");
      cy.contains('[data-cy="product-row"]', name).should("contain", "49,90");
    });
  });

  it("exclui um produto de teste", () => {
    cy.fixture("produto.json").then((produto) => {
      const name = `${produto.name} para excluir`;
      cy.criarProduto({ ...produto, name });
      cy.on("window:confirm", () => true);
      cy.contains('[data-cy="product-row"]', name).find('[data-cy="delete-product"]').click();
      cy.contains('[data-cy="product-row"]', name).should("not.exist");
    });
  });

  it("destaca produtos abaixo do estoque mínimo e deixa os demais sem alerta", () => {
    cy.fixture("produto.json").then((produto) => {
      cy.criarProduto({ ...produto, name: "Produto com estoque baixo", stock: 2, minimum_stock: 5 });
      cy.criarProduto({ ...produto, name: "Produto com estoque normal", stock: 8, minimum_stock: 5 });
      cy.contains('[data-cy="product-row"]', "Produto com estoque baixo")
        .find('[data-cy="stock-low"]')
        .should("be.visible");
      cy.contains('[data-cy="product-row"]', "Produto com estoque normal")
        .find('[data-cy="stock-low"]')
        .should("not.exist");
    });
  });

  it("avança e volta entre páginas quando há mais de dez produtos", () => {
    cy.fixture("produto.json").then((produto) => {
      Cypress._.times(12, (index) => {
        cy.criarProduto({ ...produto, name: `Produto de paginação ${index + 1}` });
      });
    });

    cy.visit("/produtos");
    cy.get('[data-cy="products-table"] tbody tr').should("have.length", 10);
    cy.get('[data-cy="products-table"] tbody').invoke("text").then((firstPage) => {
      cy.get('[data-cy="page-next"]').click();
      cy.location("search").should("contain", "page=2");
      cy.get('[data-cy="products-table"] tbody').invoke("text").should("not.eq", firstPage);
      cy.get('[data-cy="page-previous"]').click();
      cy.location("search").should("not.contain", "page=2");
    });
  });
});
