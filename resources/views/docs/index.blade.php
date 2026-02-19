@extends('layouts.docs')

@section('docs_content')
{{-- Overview --}}
<section id="overview" class="scroll-mt-24">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Documentation</h1>
    <p class="mt-2 text-gray-600">Everything you need to integrate and run Licensify.</p>

    <div class="mt-8 p-4 bg-brand-50 border border-brand-100 rounded-xl">
        <h2 class="text-lg font-semibold text-gray-900">Overview</h2>
        <p class="mt-2 text-gray-700">Licensify lets you validate license keys by domain. A client (e.g. WordPress plugin, SaaS app) sends <code class="font-mono text-sm bg-white px-1 rounded">license_key</code> and <code class="font-mono text-sm bg-white px-1 rounded">domain</code> to <code class="font-mono text-sm bg-white px-1 rounded">POST /api/validate-license</code> and receives success or error. The server checks key validity, expiry, and domain binding. First successful validation can bind the license to that domain.</p>
        <p class="mt-2 text-gray-700">Base URL: <code class="font-mono text-sm bg-white px-1 rounded">{{ url('/') }}</code>. All API responses are JSON. Send <code class="font-mono text-sm bg-white px-1 rounded">Accept: application/json</code> for validation error responses in JSON.</p>
    </div>
</section>

{{-- API Reference --}}
<section id="api-reference" class="scroll-mt-24 mt-16">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">API Reference</h2>

    <div id="validate-license" class="scroll-mt-24 mt-8">
        <h3 class="text-xl font-semibold text-gray-900">Validate license</h3>
        <p class="mt-2 text-gray-600">Check whether a license key is valid for a given domain.</p>
        <ul class="mt-3 text-gray-600 text-sm space-y-1">
            <li><strong>Endpoint:</strong> <code class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">POST {{ url('/api/validate-license') }}</code></li>
            <li><strong>Rate limit:</strong> 10 requests per minute per IP</li>
            <li><strong>Auth:</strong> None (public endpoint for client validation)</li>
        </ul>
    </div>

    <div id="request" class="scroll-mt-24 mt-8">
        <h4 class="text-lg font-semibold text-gray-900">Request</h4>
        <p class="mt-1 text-gray-600 text-sm">Content-Type: <code class="font-mono bg-gray-100 px-1 rounded">application/x-www-form-urlencoded</code> or <code class="font-mono bg-gray-100 px-1 rounded">application/json</code>.</p>
        <div class="mt-3 overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-2 px-3 font-semibold text-gray-900">Parameter</th>
                        <th class="text-left py-2 px-3 font-semibold text-gray-900">Type</th>
                        <th class="text-left py-2 px-3 font-semibold text-gray-900">Required</th>
                        <th class="text-left py-2 px-3 font-semibold text-gray-900">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr><td class="py-2 px-3 font-mono">license_key</td><td class="py-2 px-3">string</td><td class="py-2 px-3">Yes</td><td class="py-2 px-3">The license key</td></tr>
                    <tr><td class="py-2 px-3 font-mono">domain</td><td class="py-2 px-3">string</td><td class="py-2 px-3">Yes</td><td class="py-2 px-3">Domain to validate (e.g. example.com or www.example.com). Normalized server-side (lowercase, no protocol/path, www stripped)</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="responses" class="scroll-mt-24 mt-8">
        <h4 class="text-lg font-semibold text-gray-900">Responses</h4>
        <div class="mt-3 space-y-4">
            <div>
                <p class="font-medium text-gray-900">Success</p>
                <p class="text-sm text-gray-600">Status: <code class="font-mono bg-gray-100 px-1 rounded">200 OK</code></p>
                <pre class="mt-1 rounded-lg bg-gray-900 text-gray-100 p-3 font-mono text-sm overflow-x-auto"><code>{
  "status": "success",
  "message": "License validation successful"
}</code></pre>
            </div>
            <div>
                <p class="font-medium text-gray-900">Validation failed (invalid/expired/wrong domain)</p>
                <p class="text-sm text-gray-600">Status: <code class="font-mono bg-gray-100 px-1 rounded">403 Forbidden</code></p>
                <pre class="mt-1 rounded-lg bg-gray-900 text-gray-100 p-3 font-mono text-sm overflow-x-auto"><code>{
  "status": "error",
  "message": "License validation failed"
}</code></pre>
            </div>
            <div>
                <p class="font-medium text-gray-900">Invalid input (e.g. missing domain)</p>
                <p class="text-sm text-gray-600">Status: <code class="font-mono bg-gray-100 px-1 rounded">422 Unprocessable Entity</code></p>
                <pre class="mt-1 rounded-lg bg-gray-900 text-gray-100 p-3 font-mono text-sm overflow-x-auto"><code>{
  "message": "Domain is required.",
  "errors": {
    "domain": ["Domain is required."]
  }
}</code></pre>
            </div>
        </div>
    </div>

    <div id="behavior" class="scroll-mt-24 mt-8">
        <h4 class="text-lg font-semibold text-gray-900">Behavior</h4>
        <ul class="mt-3 text-gray-600 space-y-2 list-disc list-inside">
            <li>If the license has no domain set, the first successful validation <strong>binds</strong> the license to the given domain.</li>
            <li>Subsequent validations must use the same (normalized) domain.</li>
            <li>Domains are normalized: same effective domain (e.g. <code class="font-mono text-sm bg-gray-100 px-1 rounded">example.com</code> vs <code class="font-mono text-sm bg-gray-100 px-1 rounded">www.example.com</code>) is treated as one.</li>
            <li>Expired or inactive licenses return the same generic error for security (no enumeration).</li>
        </ul>
    </div>

    <div id="examples" class="scroll-mt-24 mt-8">
        <h4 class="text-lg font-semibold text-gray-900">Examples</h4>
        <p class="mt-2 text-gray-600 text-sm">cURL:</p>
        <pre class="mt-1 rounded-lg bg-gray-900 text-gray-100 p-3 font-mono text-sm overflow-x-auto"><code>curl -X POST {{ url('/api/validate-license') }} \
  -H "Accept: application/json" \
  -d "license_key=XXXX-XXXX-XXXX-XXXX" \
  -d "domain=example.com"</code></pre>
        <p class="mt-4 text-gray-600 text-sm">PHP (WordPress / plain PHP):</p>
        <pre class="mt-1 rounded-lg bg-gray-900 text-gray-100 p-3 font-mono text-sm overflow-x-auto"><code>$response = wp_remote_post($url . '/api/validate-license', [
    'timeout' => 15,
    'body'    => [
        'license_key' => $license_key,
        'domain'      => parse_url(home_url(), PHP_URL_HOST),
    ],
]);
$code = wp_remote_retrieve_response_code($response);
$body = json_decode(wp_remote_retrieve_body($response), true);
$valid = ($code === 200 && isset($body['status']) && $body['status'] === 'success');</code></pre>
        <p class="mt-3 text-gray-600 text-sm">A drop-in PHP helper with caching is available in the repo: <code class="font-mono bg-gray-100 px-1 rounded">docs/wordpress-license-client.php</code>. Use it in your plugin to validate licenses and cache the result (e.g. 24h).</p>
    </div>

    <div id="other-routes" class="scroll-mt-24 mt-8">
        <h4 class="text-lg font-semibold text-gray-900">Other API routes</h4>
        <ul class="mt-3 text-gray-600 space-y-1">
            <li><strong>GET /api/user</strong> — Returns the authenticated user (Sanctum). Requires <code class="font-mono text-sm bg-gray-100 px-1 rounded">Authorization: Bearer &lt;token&gt;</code>.</li>
        </ul>
    </div>
</section>

{{-- What works today --}}
<section id="what-works" class="scroll-mt-24 mt-16">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">What works today</h2>
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-2 px-3 font-semibold text-gray-900">Feature</th>
                    <th class="text-left py-2 px-3 font-semibold text-gray-900">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr><td class="py-2 px-3">License validation API</td><td class="py-2 px-3 text-green-600">Working</td></tr>
                <tr><td class="py-2 px-3">Domain binding (first-use locks to domain)</td><td class="py-2 px-3 text-green-600">Working</td></tr>
                <tr><td class="py-2 px-3">Expiry checking</td><td class="py-2 px-3 text-green-600">Working</td></tr>
                <tr><td class="py-2 px-3">Active/inactive toggle</td><td class="py-2 px-3 text-green-600">Working</td></tr>
                <tr><td class="py-2 px-3">Filament admin panel (create/edit licenses)</td><td class="py-2 px-3 text-green-600">Working</td></tr>
                <tr><td class="py-2 px-3">API rate limiting (60 req/min general; 10/min on validate)</td><td class="py-2 px-3 text-green-600">Present</td></tr>
                <tr><td class="py-2 px-3">CORS enabled for API</td><td class="py-2 px-3 text-green-600">Present</td></tr>
            </tbody>
        </table>
    </div>
</section>

{{-- Implemented --}}
<section id="implemented" class="scroll-mt-24 mt-16">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">Implemented</h2>
    <p class="mt-4 text-gray-600">The following items from the production checklist are already in place:</p>
    <ul class="mt-4 space-y-2 text-gray-700">
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>Input validation</strong> — <code class="font-mono text-sm bg-gray-100 px-1 rounded">license_key</code> and <code class="font-mono text-sm bg-gray-100 px-1 rounded">domain</code> validated via Form Request</li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>Domain normalization</strong> — Strip protocol, path, port; lowercase; <code class="font-mono text-sm bg-gray-100 px-1 rounded">www</code> stripped so www.example.com = example.com</li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>Stricter rate limiting</strong> — 10 requests per minute per IP on <code class="font-mono text-sm bg-gray-100 px-1 rounded">/api/validate-license</code></li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>Generic error messages</strong> — Single “License validation failed” for all failure cases; detailed reason logged server-side</li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>Auto-generate license keys</strong> — In Filament Create License, leave key empty to get format XXXX-XXXX-XXXX-XXXX</li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>WordPress client helper</strong> — <code class="font-mono text-sm bg-gray-100 px-1 rounded">docs/wordpress-license-client.php</code> with caching and clear usage</li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>Activation audit log</strong> — <code class="font-mono text-sm bg-gray-100 px-1 rounded">license_activations</code> table; every validation (success/failure) logged; viewable in Filament per license</li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>API documentation</strong> — This page</li>
        <li class="flex items-start gap-2"><span class="text-green-600 shrink-0">✓</span> <strong>Automated tests</strong> — Feature tests for valid key, invalid key, expired, inactive, domain mismatch, domain normalization, validation errors</li>
    </ul>
</section>

{{-- Remaining gaps --}}
<section id="remaining-gaps" class="scroll-mt-24 mt-16">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">Remaining gaps</h2>
    <p class="mt-4 text-gray-600">Consider adding these for production or commercial use:</p>
    <ul class="mt-4 space-y-4 text-gray-700">
        <li>
            <strong>License deactivation / transfer</strong> — Customer moves site; license is bound to old domain. Add “Deactivate” or “Reset domain” in admin, or customer-facing deactivation with verification.
        </li>
        <li>
            <strong>Product / version support</strong> — Single product only. Add <code class="font-mono text-sm bg-gray-100 px-1 rounded">product_id</code> or <code class="font-mono text-sm bg-gray-100 px-1 rounded">product_slug</code> to licenses and optional <code class="font-mono text-sm bg-gray-100 px-1 rounded">product</code> param on validate for multi-product licensing.
        </li>
        <li>
            <strong>Updates endpoint</strong> — Add <code class="font-mono text-sm bg-gray-100 px-1 rounded">GET /api/check-updates?license_key=...&domain=...&current_version=...</code> returning latest version and download URL if valid (for plugin update checks).
        </li>
        <li>
            <strong>Customer self-service portal</strong> — Let customers view their licenses and deactivate for transfer (e.g. login with license_key + email or integrate with store).
        </li>
    </ul>
</section>

{{-- Nice-to-have --}}
<section id="nice-to-have" class="scroll-mt-24 mt-16">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">Nice-to-have (later)</h2>
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left py-2 px-3 font-semibold text-gray-900">Item</th>
                    <th class="text-left py-2 px-3 font-semibold text-gray-900">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-gray-700">
                <tr><td class="py-2 px-3">Email notifications</td><td class="py-2 px-3">Notify on license expiry, first activation, deactivation</td></tr>
                <tr><td class="py-2 px-3">License tiers</td><td class="py-2 px-3">Different limits (e.g. sites count, features)</td></tr>
                <tr><td class="py-2 px-3">Webhook on validation</td><td class="py-2 px-3">Notify external systems (CRM, analytics)</td></tr>
                <tr><td class="py-2 px-3">Soft delete licenses</td><td class="py-2 px-3">Keep history instead of hard delete</td></tr>
                <tr><td class="py-2 px-3">HTTPS enforcement</td><td class="py-2 px-3">Force SSL in production</td></tr>
                <tr><td class="py-2 px-3">Environment config</td><td class="py-2 px-3"><code class="font-mono bg-gray-100 px-1 rounded">APP_DEBUG=false</code>, proper <code class="font-mono bg-gray-100 px-1 rounded">APP_URL</code> for production</td></tr>
            </tbody>
        </table>
    </div>
</section>

{{-- WordPress integration --}}
<section id="wordpress-checklist" class="scroll-mt-24 mt-16">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">WordPress integration checklist</h2>
    <p class="mt-4 text-gray-600">To use this server with a WordPress plugin (e.g. M-Pesa plugin):</p>
    <ol class="mt-4 list-decimal list-inside space-y-2 text-gray-700">
        <li><strong>Add license settings</strong> in the plugin: license key input, “Activate” / “Deactivate” buttons.</li>
        <li><strong>On activation:</strong> POST to <code class="font-mono text-sm bg-gray-100 px-1 rounded">{{ url('/api/validate-license') }}</code> with <code class="font-mono text-sm bg-gray-100 px-1 rounded">license_key</code> and <code class="font-mono text-sm bg-gray-100 px-1 rounded">domain</code> (from <code class="font-mono text-sm bg-gray-100 px-1 rounded">site_url()</code> or <code class="font-mono text-sm bg-gray-100 px-1 rounded">parse_url(home_url(), PHP_URL_HOST)</code>).</li>
        <li><strong>Cache result</strong> (transient, 12–24h) to avoid repeated API calls.</li>
        <li><strong>Gate features:</strong> If validation fails, show “License required” and limit functionality.</li>
        <li><strong>Optional:</strong> Periodic re-validation (e.g. weekly cron) or on plugin load.</li>
    </ol>
    <p class="mt-4 text-gray-600">Use the provided <code class="font-mono text-sm bg-gray-100 px-1 rounded">docs/wordpress-license-client.php</code> helper: instantiate with your API URL and product slug, call <code class="font-mono text-sm bg-gray-100 px-1 rounded">validate($license_key, $domain)</code>, and optionally <code class="font-mono text-sm bg-gray-100 px-1 rounded">clearCache()</code> on deactivation.</p>
</section>

{{-- Priority order --}}
<section id="priority" class="scroll-mt-24 mt-16">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">Recommended priority order</h2>
    <p class="mt-4 text-gray-600">If you are implementing remaining items, this order is suggested:</p>
    <ol class="mt-4 list-decimal list-inside space-y-1 text-gray-700">
        <li>Input validation — <span class="text-green-600">Implemented</span></li>
        <li>Domain normalization — <span class="text-green-600">Implemented</span></li>
        <li>Stricter rate limiting on validate — <span class="text-green-600">Implemented (10/min)</span></li>
        <li>Generic error messages — <span class="text-green-600">Implemented</span></li>
        <li>Auto-generate license keys — <span class="text-green-600">Implemented</span></li>
        <li>WordPress client helper — <span class="text-green-600">Implemented</span></li>
        <li>Activation audit log — <span class="text-green-600">Implemented</span></li>
        <li>API documentation — <span class="text-green-600">Implemented</span></li>
        <li>License deactivation / transfer</li>
        <li>Automated tests — <span class="text-green-600">Implemented</span></li>
    </ol>
</section>

{{-- Conclusion --}}
<section id="conclusion" class="scroll-mt-24 mt-16 pb-8">
    <h2 class="text-2xl font-bold text-gray-900 border-b border-gray-200 pb-2">Conclusion</h2>
    <p class="mt-4 text-gray-700">Licensify is functional and suitable for internal or early use. Critical items (validation, domain normalization, security, client integration) are implemented. Remaining gaps (deactivation, multi-product, updates endpoint, customer portal) will make it more robust for commercial sale. With the current implementation you can create accounts, generate license keys, validate them via API, and view activation history in the dashboard.</p>
</section>
@endsection
