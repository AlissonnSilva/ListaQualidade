import { defineConfig } from "cypress";
import { execFileSync } from "node:child_process";
import { fileURLToPath } from "node:url";

const projectRoot = fileURLToPath(new URL(".", import.meta.url));

export default defineConfig({
  video: false,
  viewportWidth: 1280,
  viewportHeight: 900,
  e2e: {
    baseUrl: "http://localhost:8000",
    supportFile: "cypress/support/e2e.js",
    setupNodeEvents(on, config) {
      let databaseWasReset = false;

      on("task", {
        resetDatabase() {
          if (databaseWasReset) return null;

          execFileSync(
            process.platform === "win32" ? "docker-compose.exe" : "docker-compose",
            ["exec", "-T", "app", "php", "artisan", "migrate:fresh", "--seed", "--force"],
            { cwd: projectRoot, stdio: "pipe" },
          );

          databaseWasReset = true;
          return null;
        },
      });

      return config;
    },
  },
});
