
# AI Instruction Template

Purpose
-------

This document provides clear, actionable instructions for AI assistants working on this repository. Use it to describe the task goal, constraints, acceptable outputs, and verification steps so the AI can produce reliable, reviewable changes.

Structure
---------

- **Title**: Short descriptive name for the task.
- **Goal**: One-sentence objective the AI should achieve.
- **Context**: Minimal repository context and relevant file paths.
- **Requirements**: Precise functional requirements and constraints.
- **Deliverables**: Files to create or modify and format expectations.
- **Acceptance Criteria**: Tests, linters, or manual checks that must pass.
- **Examples**: Small input/output examples or snippets.
- **Notes**: Any non-functional constraints (style, security, privacy).

Guidelines for Writing Tasks
---------------------------

1. Be concise but specific. Prefer explicit file names and line ranges.
2. State any compatibility or environment constraints (PHP version, frameworks).
3. When asking for code changes, include the desired behavior and a small example.
4. State whether automated tests or manual verification are required.

AI Behavior Expectations
------------------------

- Make minimal, focused edits that solve the stated problem.
- Preserve existing code style and conventions unless asked to refactor.
- Run repository checks locally when possible and report results.
- When uncertain, ask a single clarifying question before making broad changes.

Security & Privacy
------------------

- Never add secrets, API keys, or credentials into repo files.
- If a change involves user data handling, mention privacy implications and validation.

Commit & PR Guidelines
----------------------

- Use a clear commit message describing the change and its purpose.
- Group related changes into a single commit when possible.
- If multiple independent fixes are required, create multiple commits/PRs.

Example Task
------------

- Title: "Fix missing session check on `save-report.php`"
- Goal: "Ensure `save-report.php` only accepts requests from authenticated users."
- Context: `src/php/doctor/save-report.php` and `src/php/session.php`
- Requirements:
	- Add a session check at the top of `save-report.php` using existing `session.php` utilities.
	- Return HTTP 401 for unauthenticated requests.
- Deliverables:
	- Updated `src/php/doctor/save-report.php` with session verification.
	- A short note in this instruction file describing the change.
- Acceptance Criteria:
	- Manual test: calling endpoint without session returns 401.
	- No linter or syntax errors introduced.

How to Request Revisions
------------------------

If the change is incomplete or requires clarification, leave a concise comment in the PR describing what's missing and what information is needed. Prefer a single question that can be answered to unblock further edits.

Contact & Ownership
-------------------

List a repo owner, maintainer, or team contact here when available so reviewers know who to ask for context.

---

Last updated: 2026-05-24

