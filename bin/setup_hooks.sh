#!/bin/bash
if `cd .git/hooks/`
then
    cp bin/pre-commit .git/hooks/pre-commit
    chmod +x .git/hooks/pre-commit
fi
