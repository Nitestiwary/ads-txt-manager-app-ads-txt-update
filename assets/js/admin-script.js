/**
 * Script managing real-time validations, settings warnings, and UX components.
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        var $editor = $('.atm-code-editor');

        if ($editor.length) {
            // Read if auto-validation is enabled
            var shouldValidate = $editor.data('validate') == '1';

            if (shouldValidate) {
                // Initialize validation on load
                performLiveValidation($editor);

                // Run live check on type/paste with 400ms debounce
                var timer;
                $editor.on('input propertychange', function() {
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        performLiveValidation($editor);
                    }, 400);
                });
            }
        }

        /**
         * Perform syntax validation check.
         */
        function performLiveValidation($el) {
            var content = $el.val();
            var $resultBox = $('#atm-live-validation-result');
            var $resultBody = $resultBox.find('.atm-validation-body');

            if (!content || !content.trim()) {
                $resultBox.addClass('hidden');
                return;
            }

            var lines = content.split('\n');
            var errors = [];
            var warnings = [];
            var seen = {};
            var totalCount = 0;
            var duplicateCount = 0;

            for (var i = 0; i < lines.length; i++) {
                var lineNum = i + 1;
                var line = lines[i].trim();

                // Skip comments and empty lines
                if (!line || line.indexOf('#') === 0) {
                    continue;
                }

                totalCount++;

                // Duplicate entry check
                var normalized = line.toLowerCase().replace(/\s+/g, '');
                if (seen[normalized]) {
                    duplicateCount++;
                    warnings.push({
                        line: lineNum,
                        message: 'Duplicate entry detected (matches line ' + seen[normalized] + ')'
                    });
                    continue;
                }
                seen[normalized] = lineNum;

                // Standard IAB format: Domain, Publisher ID, Relationship, TAG/Cert ID (Optional)
                var parts = line.split(',');
                var count = parts.length;

                if (count < 3 || count > 4) {
                    errors.push({
                        line: lineNum,
                        message: 'Invalid formatting. Must contain 3 or 4 comma-separated values (Domain, Publisher ID, Relationship, optional Certification Authority ID).'
                    });
                    continue;
                }

                var domain = parts[0].trim();
                var pubId = parts[1].trim();
                var relation = parts[2].trim().toUpperCase();

                // 1. Validate domain structure
                var domainRegex = /^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/i;
                if (!domainRegex.test(domain)) {
                    errors.push({
                        line: lineNum,
                        message: 'Invalid domain structure: "' + domain + '"'
                    });
                }

                // 2. Validate Publisher ID presence
                if (!pubId) {
                    errors.push({
                        line: lineNum,
                        message: 'Missing publisher/seller ID.'
                    });
                }

                // 3. Validate relationship
                if (relation !== 'DIRECT' && relation !== 'RESELLER') {
                    errors.push({
                        line: lineNum,
                        message: 'Invalid relationship: "' + relation + '". Must be DIRECT or RESELLER (case insensitive).'
                    });
                }
            }

            // Render Results HTML
            if (errors.length > 0 || warnings.length > 0) {
                var html = '<div class="atm-log-entries">';
                
                errors.forEach(function(err) {
                    html += '<div class="atm-log-item log-error">';
                    html += '<span class="dashicons dashicons-dismiss"></span>';
                    html += '<span><strong>Line ' + err.line + ':</strong> ' + err.message + '</span>';
                    html += '</div>';
                });

                warnings.forEach(function(warn) {
                    html += '<div class="atm-log-item log-warning">';
                    html += '<span class="dashicons dashicons-warning"></span>';
                    html += '<span><strong>Line ' + warn.line + ':</strong> ' + warn.message + '</span>';
                    html += '</div>';
                });

                html += '</div>';

                $resultBody.html(html);
                $resultBox.removeClass('hidden');
            } else {
                $resultBox.addClass('hidden');
            }
        }
    });

})(jQuery);
