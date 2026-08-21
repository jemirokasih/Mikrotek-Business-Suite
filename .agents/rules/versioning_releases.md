# Versioning, Branching, and Release Rules

1. **Always bump version number** for any changes, bug fixes, or feature updates in:
   - `package.json`
   - `composer.json`
   - Update `MIKROTEK_CHANGELOG.md` with release notes under the new version header.

2. **Branching Workflow**:
   - All development, bug fixes, and feature additions MUST be committed and pushed to the `dev` branch.

3. **Merge to Main & GitHub Release Rules (CRITICAL)**:
   - **NEVER automatically merge `dev` to `main`.**
   - **NEVER automatically create a GitHub release.**
   - ONLY merge `dev` to `main` and publish a GitHub Release (`gh release create vX.Y.Z`) when the user explicitly requests/instructs to merge to `main` or create a release.
