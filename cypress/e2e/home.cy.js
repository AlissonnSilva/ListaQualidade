describe("Página inicial", () => {
  beforeEach(() => {
    cy.visit("/");
  });

  it("visita o painel da Doce Gestão", () => {
    cy.contains("Doce Gestão").should("be.visible");
  });
});
