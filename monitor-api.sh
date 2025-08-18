#!/bin/bash

# API Monitoring Script for SpentTracker
echo "🚀 Starting API Monitor for SpentTracker"
echo "=================================================="
echo "Watching for API requests and responses..."
echo "Press Ctrl+C to stop"
echo ""

# Watch Laravel logs in real-time, filtering for API-related entries
docker compose exec app tail -f storage/logs/laravel.log | grep --line-buffered "API Request\|API Response" | while read line; do
    # Parse the timestamp and message
    timestamp=$(echo "$line" | grep -o '\[.*\]' | head -1)
    
    if echo "$line" | grep -q "API Request"; then
        method=$(echo "$line" | grep -o '"method":"[^"]*"' | cut -d'"' -f4)
        url=$(echo "$line" | grep -o '"url":"[^"]*"' | cut -d'"' -f4 | sed 's/http:\/\/localhost:8092//')
        ip=$(echo "$line" | grep -o '"ip":"[^"]*"' | cut -d'"' -f4)
        
        echo "📥 $timestamp [$method] $url from $ip"
    fi
    
    if echo "$line" | grep -q "API Response"; then
        status=$(echo "$line" | grep -o '"status":[0-9]*' | cut -d':' -f2)
        duration=$(echo "$line" | grep -o '"duration_ms":[0-9.]*' | cut -d':' -f2)
        
        # Color code status
        if [[ $status -ge 200 && $status -lt 300 ]]; then
            status_color="✅ $status"
        elif [[ $status -ge 400 && $status -lt 500 ]]; then
            status_color="❌ $status"
        else
            status_color="⚠️  $status"
        fi
        
        echo "📤 $timestamp Response: $status_color (${duration}ms)"
        echo ""
    fi
done