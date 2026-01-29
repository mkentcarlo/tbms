#!/bin/bash

# TBMS Windows .exe Builder Helper Script
# This script helps you build Windows .exe from Mac using GitHub Actions

echo "=========================================="
echo "TBMS Windows .exe Builder (from Mac)"
echo "=========================================="
echo ""

# Check if git repo
if [ ! -d ".git" ]; then
    echo "⚠️  Not a git repository."
    echo "Initializing git repository..."
    git init
    git add .
    git commit -m "Initial commit"
    echo ""
fi

# Check if GitHub remote exists
if ! git remote | grep -q "origin"; then
    echo "⚠️  No GitHub remote found."
    echo ""
    echo "To use GitHub Actions, you need to:"
    echo "1. Create a GitHub repository"
    echo "2. Add it as remote:"
    echo "   git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git"
    echo ""
    read -p "Do you want to continue anyway? (y/n) " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Check if workflow file exists
if [ ! -f ".github/workflows/build-windows.yml" ]; then
    echo "⚠️  GitHub workflow file not found!"
    echo "Creating it now..."
    mkdir -p .github/workflows
    # The workflow file should already exist, but just in case
    echo "Please ensure .github/workflows/build-windows.yml exists"
    exit 1
fi

echo "✅ GitHub workflow file found"
echo ""

# Check if changes need to be committed
if [ -n "$(git status --porcelain)" ]; then
    echo "📝 You have uncommitted changes."
    echo ""
    git status --short
    echo ""
    read -p "Commit and push changes? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git add .
        git commit -m "Prepare for Windows build"
        echo ""
    fi
fi

# Check if needs to push
LOCAL=$(git rev-parse @)
REMOTE=$(git rev-parse @{u} 2>/dev/null || echo "none")

if [ "$REMOTE" != "none" ] && [ "$LOCAL" != "$REMOTE" ]; then
    echo "📤 Pushing to GitHub..."
    git push
    echo ""
fi

echo "=========================================="
echo "Next Steps:"
echo "=========================================="
echo ""
echo "1. Go to your GitHub repository:"
echo "   https://github.com/YOUR_USERNAME/YOUR_REPO"
echo ""
echo "2. Click the 'Actions' tab"
echo ""
echo "3. Click 'Build Windows Installer' workflow"
echo ""
echo "4. Click 'Run workflow' button (top right)"
echo ""
echo "5. Click 'Run workflow' (green button)"
echo ""
echo "6. Wait 2-5 minutes for build to complete"
echo ""
echo "7. Download the .exe from 'Artifacts' section"
echo ""
echo "=========================================="
echo ""

# Try to open GitHub Actions page if possible
if command -v open &> /dev/null; then
    REMOTE_URL=$(git remote get-url origin 2>/dev/null)
    if [ -n "$REMOTE_URL" ]; then
        # Convert SSH to HTTPS if needed
        if [[ $REMOTE_URL == git@* ]]; then
            REMOTE_URL=$(echo $REMOTE_URL | sed 's/git@\(.*\):\(.*\)\.git/https:\/\/\1\/\2/g')
        fi
        ACTIONS_URL="${REMOTE_URL}/actions"
        echo "Opening GitHub Actions page..."
        open "$ACTIONS_URL" 2>/dev/null || echo "Could not open browser. Go to: $ACTIONS_URL"
    fi
fi

echo ""
echo "Done! Follow the steps above to build your Windows .exe"
