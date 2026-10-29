import { existsSync, readFileSync } from "node:fs";
import { spawnSync } from "node:child_process";
import { createRequire } from "node:module";
import { dirname, join } from "node:path";
import { tmpdir } from "node:os";

const require = createRequire(import.meta.url);
const cypressPackage = dirname(dirname(require.resolve("cypress")));
const cypressCli = join(cypressPackage, "bin", "cypress");
const runnerEnvironment = { ...process.env };
const cypressVersion = JSON.parse(readFileSync(join(cypressPackage, "package.json"), "utf8")).version;
const temporaryCache = join(tmpdir(), "confeitaria-cypress-cache");

// Some IDE sessions set this for Electron apps; Cypress needs a normal Electron process.
delete runnerEnvironment.ELECTRON_RUN_AS_NODE;

// Reuse a verified temporary binary cache when the default Windows cache is unavailable.
if (
  process.platform === "win32" &&
  !runnerEnvironment.CYPRESS_CACHE_FOLDER &&
  existsSync(join(temporaryCache, cypressVersion, "Cypress", "Cypress.exe"))
) {
  runnerEnvironment.CYPRESS_CACHE_FOLDER = temporaryCache;
}

const result = spawnSync(process.execPath, [cypressCli, ...process.argv.slice(2)], {
  cwd: process.cwd(),
  env: runnerEnvironment,
  stdio: "inherit",
});

if (result.error) {
  console.error(result.error);
  process.exitCode = 1;
} else {
  process.exitCode = result.status ?? 1;
}
