import "./commands";

// A suíte usa a base local de desenvolvimento, que deve conter apenas dados descartáveis.
before(() => {
  cy.task("resetDatabase");
});
