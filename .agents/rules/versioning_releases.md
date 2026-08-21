# Versioning and Release Rules

1. **Versioning & Changelog Protocol**:
   - For every change, feature addition, UI/UX improvement, or bug fix:
     - Update `MIKROTEK_CHANGELOG.md` with detailed entries.
     - Bump the version number in `package.json` and `composer.json` (e.g. `1.2.0` -> `1.2.1` -> `1.2.2` for patch/fixes, or `1.3.0` for major updates).
2. **Merging to Main & GitHub Releases**:
   - Whenever merging to `main` branch or requested by user:
     - ALWAYS create a GitHub Release using `gh release create vX.Y.Z --title "vX.Y.Z - Release Title" --notes "..."`.
     - Ensure the release notes include full changelog details.
