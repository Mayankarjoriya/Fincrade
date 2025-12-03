<?php
// Start session for CSRF token
session_start();
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fincrade® – Compare & Apply for Loans Online</title>
  <meta name="description" content="Fincrade helps you compare credit cards and loans — Personal, Business, Home, Car, and more. Instant eligibility check, low interest rates, quick approval." />
  <meta name="keywords" content="Fincrade, loans, personal loan, business loan, credit card, DSA partner, compare interest rates, EMI calculator" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#0f766e',
              50: '#ecfdf5',
              100: '#d1fae5',
              200: '#a7f3d0',
              300: '#6ee7b7',
              400: '#34d399',
              500: '#10b981',
              600: '#0f766e',
              700: '#115e59',
              800: '#134e4a',
              900: '#042f2e'
            }
          },
          boxShadow: {
            soft: '0 10px 30px rgba(0,0,0,.06)'
          }
        }
      }
    }

  </script>
  <style>
    html { scroll-behavior: smooth; }

    .root{
        --primary-color: #1e293b;
    }
    body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }

    /*New CSS for New EMI Calculator*/
    .shadow-soft {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    /* Style range inputs consistently */
    input[type=range] {
        -webkit-appearance: none;
        width: 100%;
        height: 6px;
        background: #d1d5db;
        border-radius: 3px;
        outline: none;
        opacity: 0.7;
        transition: opacity .15s ease-in-out;
    }
    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        background: var(--primary-color);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0 0 4px rgba(30, 41, 59, 0.2);
    }
    input:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 1px var(--primary-color);
    }
    /* Table Styling */
    #scheduleWrap table th, #scheduleWrap table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
    }
    #scheduleWrap table th {
        font-weight: 600;
        background-color: #f9fafb;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .mini-nowrap th, .mini-nowrap td {
        white-space: nowrap;
    }
    .glass { backdrop-filter: saturate(120%) blur(8px); background: rgba(255,255,255,.7); }
    .hidden-hp { position:absolute; left:-50000px; top:auto; width:1px; height:1px; overflow:hidden; }
  </style>

  <!-- Open Graph -->
  <meta property="og:title" content="Fincrade – Compare & Apply for Loans Online" />
  <meta property="og:description" content="Personal, Business, Home, Car & more. Instant eligibility check and fast approvals." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://www.fincrade.com/" />
  <meta property="og:image" content="https://via.placeholder.com/1200x630.png?text=Fincrade" />
  <link rel="icon" href="/favicon.ico" />
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Fincrade",
    "url": "https://www.fincrade.com/",
    "logo": "https://www.fincrade.com/logo.png",
    "sameAs": [
      "https://www.facebook.com/fincrade",
      "https://www.instagram.com/fincrade",
      "https://www.linkedin.com/company/fincrade"
    ]
  }
  </script>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Fincrade",
    "url": "https://www.fincrade.com/",
    "potentialAction": {
      "@type": "SearchAction",
      "target": "https://www.fincrade.com/?q={search_term_string}",
      "query-input": "required name=search_term_string"
    }
  }
  </script>
</head>
<body class="bg-gray-50 text-gray-900">
  <!-- Top Ribbon -->
  <div class="w-full bg-primary-700 text-white text-sm">
    <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
      <div>📞 <a href="tel:+919999999999" class="underline underline-offset-2">+91 87641 00245</a> • ✉️ <a href="mailto:support@fincrade.com" class="underline underline-offset-2">hello@fincrade.com</a></div>
      <div class="hidden sm:flex gap-4">
        <a href="#eligibility" class="hover:opacity-90">Eligibility</a>
        <a href="#emi" class="hover:opacity-90">EMI Calculator</a>
        <a href="#contact" class="hover:opacity-90">Contact</a>
      </div>
    </div>
  </div>

  <!-- Header / Nav -->
  <header class="sticky top-0 z-40 bg-white/90 glass border-b">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <a href="#home" class="flex items-center gap-2 group">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-primary-600">
            <path d="M12 3l8.66 5v8l-8.66 5L3.34 16V8L12 3z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M12 8l5 3v6l-5 3-5-3V11l5-3z" fill="currentColor" opacity=".12"/>
          </svg>
          <span class="font-extrabold text-xl tracking-tight">Fincrade</span>
        </a>
        <nav class="hidden md:flex items-center gap-6 font-medium">
          <a href="#products" class="hover:text-primary-700">Loan</a>
                    <a href="insurance.php" class="hover:text-primary-700">Insurance</a>
          <a href="services.php" class="hover:text-primary-700">Services</a>

          <a href="#why" class="hover:text-primary-700">Why Fincrade</a>
          <a href="#partner" class="hover:text-primary-700">Become Our Partner</a>
          <a href="partner/login.php" class="ml-2 px-4 py-2 rounded-xl bg-primary-600 text-white shadow-soft hover:bg-primary-700">Partner Login</a>
        </nav>
        <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100" aria-label="Open Menu">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>
    <div id="mobileMenu" class="md:hidden hidden border-t bg-white">
      <div class="px-4 py-3 flex flex-col gap-3">
        <a href="#products" class="py-1">Products</a>
        <a href="#why" class="py-1">Why Fincrade</a>
        <a href="#emi" class="py-1">EMI</a>
        <a href="#faq" class="py-1">FAQ</a>
        <a href="#partner" class="py-1">Partner</a>
        <a href="insurance.php" class="py-1">Insurance</a>
        <a href="partner/login.php" class="py-2 text-center rounded-xl bg-primary-600 text-white">Partner Login</a>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section id="home" class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-primary-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-24 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-100 text-primary-700 text-xs font-semibold mb-4">
          <span>✔️ RBI Compliant Partners</span><span>•</span><span>India-wide</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Compare & Apply for <span class="text-primary-700">Loans</span> in Minutes</h1>
        <p class="mt-4 text-lg text-gray-700">Personal • Business • Home • Car • Credit Cards. Get pre-qualified offers, lowest interest rates, and super-fast approvals with Fincrade.</p>
        <ul class="mt-6 space-y-2 text-gray-700">
          <li class="flex items-start gap-2"><span>✅</span> Minimal documentation</li>
          <li class="flex items-start gap-2"><span>✅</span> Transparent fees — no surprises</li>
          <li class="flex items-start gap-2"><span>✅</span> Dedicated advisor till disbursal</li>
        </ul>
        <div class="mt-8 flex gap-3">
          <a href="#apply" class="px-5 py-3 rounded-xl bg-primary-600 text-white font-semibold shadow-soft hover:bg-primary-700">Check Eligibility</a>
          <a href="#products" class="px-5 py-3 rounded-xl border font-semibold hover:bg-gray-50">Explore Products</a>
        </div>
      </div>
      <div class="bg-white rounded-2xl p-6 md:p-8 shadow-soft border">
        <h3 class="text-xl font-bold mb-4">Start Your Application</h3>
        <form id="leadForm" class="grid grid-cols-1 gap-3" method="POST" action="send.php" novalidate>
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES); ?>">
          <input type="hidden" name="form_type" value="lead">
          <label class="hidden-hp">Leave this field empty <input type="text" name="website" autocomplete="off"></label>
          <div class="grid md:grid-cols-2 gap-3">
            <input required name="name" class="w-full rounded-xl border p-3" placeholder="Full Name" />
            <input required name="phone" pattern="^[6-9][0-9]{9}$" class="w-full rounded-xl border p-3" placeholder="Mobile (10 digits)" />
          </div>
          <div class="grid md:grid-cols-2 gap-3">
            <input required type="email" name="email" class="w-full rounded-xl border p-3" placeholder="Email" />
            <select name="product" required class="w-full rounded-xl border p-3 bg-white">
              <option value="" disabled selected>Select Product</option>
              <option>Personal Loan</option>
              <option>Business Loan</option>
              <option>Home Loan</option>
              <option>Loan Against Property</option>
              <option>Car Loan</option>
              <option>Credit Card</option>
              <option>Gold Loan</option>
            </select>
          </div>
          <div class="grid md:grid-cols-2 gap-3">
            <input name="city" class="w-full rounded-xl border p-3" placeholder="City" />
            <input type="number" min="0" step="1000" name="income" class="w-full rounded-xl border p-3" placeholder="Monthly Income (₹)" />
          </div>
          <label class="flex items-start gap-2 text-sm text-gray-600"><input type="checkbox" required class="mt-1"> I agree to the <a href="#" class="underline ms-1">Terms</a> & <a href="#" class="underline">Privacy Policy</a>.</label>
          <button class="mt-2 rounded-xl bg-primary-600 text-white py-3 font-semibold hover:bg-primary-700">Get Offers</button>
          <?php if (!empty($_GET['status']) && $_GET['status']==='ok' && !empty($_GET['f']) && $_GET['f']==='lead'): ?>
            <p class="text-sm text-green-700">Thank you! We’ll reach out shortly.</p>
          <?php elseif (!empty($_GET['status']) && $_GET['status']==='error' && !empty($_GET['f']) && $_GET['f']==='lead'): ?>
            <p class="text-sm text-red-700">Sorry, something went wrong. Please try again.</p>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </section>

  <!-- Logos / Social Proof -->
  <section class="py-10 bg-white border-y">
    <div class="max-w-7xl mx-auto px-4">
      <p class="text-center text-sm text-gray-500 mb-5">Tie-up with top banks & NBFC </p>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-6 opacity-80">
        <div class="h-10 bg-gray-100 rounded-lg"></div>
        <div class="h-10 bg-gray-100 rounded-lg"></div>
        <div class="h-10 bg-gray-100 rounded-lg"></div>
        <div class="h-10 bg-gray-100 rounded-lg"></div>
        <div class="h-10 bg-gray-100 rounded-lg"></div>
        <div class="h-10 bg-gray-100 rounded-lg"></div>
      </div>
    </div>
  </section>

  <!-- Products -->
  <section id="products" class="py-16">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex items-end justify-between mb-8">
        <h2 class="text-3xl font-extrabold">Loan Products</h2>
        <a href="#apply" class="text-primary-700 font-semibold">Check Eligibility →</a>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Cards -->
        <article class="bg-white border rounded-2xl p-6 shadow-soft">
          <h3 class="text-xl font-bold">Personal Loan</h3>
          <p class="mt-2 text-gray-600">Up to ₹40L • Tenure 12–72 months • Instant approvals</p>
          <ul class="mt-3 space-y-1 text-sm text-gray-700">
            <li>• Rates from 10.99% p.a.</li>
            <li>• Salaried & self‑employed</li>
          </ul>
          <a href="#apply" class="mt-4 inline-block px-4 py-2 rounded-xl bg-gray-900 text-white">Apply</a>
        </article>
        <article class="bg-white border rounded-2xl p-6 shadow-soft">
          <h3 class="text-xl font-bold">Business Loan</h3>
          <p class="mt-2 text-gray-600">Unsecured up to ₹50L • Working capital • OD/CC</p>
          <ul class="mt-3 space-y-1 text-sm text-gray-700">
            <li>• Rates from 12.5% p.a.</li>
            <li>• Fast assessment</li>
          </ul>
          <a href="#apply" class="mt-4 inline-block px-4 py-2 rounded-xl bg-gray-900 text-white">Apply</a>
        </article>
        <article class="bg-white border rounded-2xl p-6 shadow-soft">
          <h3 class="text-xl font-bold">Home Loan</h3>
          <p class="mt-2 text-gray-600">Up to ₹5 Cr • Balance transfer • Top‑up</p>
          <ul class="mt-3 space-y-1 text-sm text-gray-700">
            <li>• Rates from 8.5% p.a.</li>
            <li>• Long tenures</li>
          </ul>
          <a href="#apply" class="mt-4 inline-block px-4 py-2 rounded-xl bg-gray-900 text-white">Apply</a>
        </article>
        <article class="bg-white border rounded-2xl p-6 shadow-soft">
          <h3 class="text-xl font-bold">Loan Against Property</h3>
          <p class="mt-2 text-gray-600">Residential/Commercial mortgage</p>
          <ul class="mt-3 space-y-1 text-sm text-gray-700">
            <li>• Rates from 9.75% p.a.</li>
            <li>• Flexible tenure</li>
          </ul>
          <a href="#apply" class="mt-4 inline-block px-4 py-2 rounded-xl bg-gray-900 text-white">Apply</a>
        </article>
        <article class="bg-white border rounded-2xl p-6 shadow-soft">
          <h3 class="text-xl font-bold">Car Loan</h3>
          <p class="mt-2 text-gray-600">New/Used cars • Quick disbursal</p>
          <ul class="mt-3 space-y-1 text-sm text-gray-700">
            <li>• Rates from 8.9% p.a.</li>
            <li>• Up to 90% on-road</li>
          </ul>
          <a href="#apply" class="mt-4 inline-block px-4 py-2 rounded-xl bg-gray-900 text-white">Apply</a>
        </article>
        <article class="bg-white border rounded-2xl p-6 shadow-soft">
          <h3 class="text-xl font-bold">Credit Cards</h3>
          <p class="mt-2 text-gray-600">Rewards • Cashback • Travel & fuel benefits</p>
          <ul class="mt-3 space-y-1 text-sm text-gray-700">
            <li>• Lifetime free options</li>
            <li>• Instant e‑KYC</li>
          </ul>
          <a href="#apply" class="mt-4 inline-block px-4 py-2 rounded-xl bg-gray-900 text-white">Apply</a>
        </article>
      </div>
    </div>
  </section>

  <!-- Why Fincrade -->
  <section id="why" class="py-16 bg-white border-y">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-extrabold mb-8">Why choose Fincrade?</h2>
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 border rounded-2xl shadow-soft bg-gradient-to-br from-white to-primary-50">
          <h4 class="font-bold">Best Offers</h4>
          <p class="text-gray-600 mt-2">We negotiate with multiple banks/NBFCs to secure the lowest rate.</p>
        </div>
        <div class="p-6 border rounded-2xl shadow-soft">
          <h4 class="font-bold">Fast Turnaround</h4>
          <p class="text-gray-600 mt-2">Digital onboarding & quick document pickup for faster disbursal.</p>
        </div>
        <div class="p-6 border rounded-2xl shadow-soft">
          <h4 class="font-bold">Transparent Process</h4>
          <p class="text-gray-600 mt-2">No hidden charges. Clear fees and timelines from day one.</p>
        </div>
        <div class="p-6 border rounded-2xl shadow-soft">
          <h4 class="font-bold">Pan‑India Support</h4>
          <p class="text-gray-600 mt-2">Serve major metros & Tier‑2/3 cities with local experts.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Eligibility Snapshot -->
  <section id="eligibility" class="py-16">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <h2 class="text-3xl font-extrabold">Basic Eligibility</h2>
        <ul class="mt-4 space-y-3 text-gray-700">
          <li>• Age 21–60 for salaried, 21–65 for self‑employed</li>
          <li>• Minimum monthly income ₹15,000 (city wise varies)</li>
          <li>• CIBIL score 700+ preferred (we also help with lower scores)</li>
          <li>• Valid KYC, bank statements & income proof</li>
        </ul>
        <a href="#apply" class="mt-6 inline-block px-5 py-3 rounded-xl bg-primary-600 text-white">Check in 60 seconds</a>
      </div>
      <div class="bg-white border rounded-2xl p-6 shadow-soft">
        <h3 class="text-xl font-bold">Documents (indicative)</h3>
        <div class="grid sm:grid-cols-2 gap-4 mt-4 text-gray-700">
          <ul class="space-y-2 list-disc list-inside">
            <li>Aadhaar & PAN</li>
            <li>Selfie/Photo</li>
            <li>Current Address Proof</li>
          </ul>
          <ul class="space-y-2 list-disc list-inside">
            <li>Last 6 months Bank Statement</li>
            <li>Salary Slips / ITR</li>
            <li>Company Proof (if business)</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

<!--   EMI Calculator original EMI calculator -->
<!--  <section id="emi" class="py-16 bg-white border-y">-->
<!--    <div class="max-w-7xl mx-auto px-4">-->
<!--      <h2 class="text-3xl font-extrabold">EMI Calculator</h2>-->
<!--      <div class="mt-6 grid lg:grid-cols-2 gap-8">-->
<!--        <div class="bg-white border rounded-2xl p-6 shadow-soft">-->
<!--          <div class="grid sm:grid-cols-2 gap-4">-->
<!--            <label class="block text-sm">Loan Amount (₹)-->
<!--              <input id="amt" type="number" min="10000" step="1000" value="500000" class="w-full mt-1 rounded-xl border p-3" />-->
<!--            </label>-->
<!--            <label class="block text-sm">Interest (p.a. %)-->
<!--              <input id="roi" type="number" min="5" step="0.1" value="11.5" class="w-full mt-1 rounded-xl border p-3" />-->
<!--            </label>-->
<!--            <label class="block text-sm">Tenure (months)-->
<!--              <input id="ten" type="number" min="6" step="1" value="36" class="w-full mt-1 rounded-xl border p-3" />-->
<!--            </label>-->
<!--            <div class="flex items-end">-->
<!--              <button id="calcBtn" class="w-full rounded-xl bg-gray-900 text-white py-3">Calculate</button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="bg-white border rounded-2xl p-6 shadow-soft">-->
<!--          <h4 class="font-bold">Results</h4>-->
<!--          <div class="mt-4 grid sm:grid-cols-3 gap-4 text-center">-->
<!--            <div class="p-4 border rounded-xl">-->
<!--              <div class="text-sm text-gray-600">Monthly EMI</div>-->
<!--              <div id="emiOut" class="text-2xl font-extrabold mt-1">—</div>-->
<!--            </div>-->
<!--            <div class="p-4 border rounded-xl">-->
<!--              <div class="text-sm text-gray-600">Total Interest</div>-->
<!--              <div id="intOut" class="text-2xl font-extrabold mt-1">—</div>-->
<!--            </div>-->
<!--            <div class="p-4 border rounded-xl">-->
<!--              <div class="text-sm text-gray-600">Total Payment</div>-->
<!--              <div id="totOut" class="text-2xl font-extrabold mt-1">—</div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <p class="text-xs text-gray-500 mt-3">*Illustrative. Actuals vary by bank, profile & documentation.</p>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </section>-->

<!--  EMI Calculator (New Version)-->
<!--  Without JS Calculator-->

<!--  ................................................-->

  <!-- Calculator Section (Version-2.0) -->
  <section id="emi" class="py-12">
      <div class="max-w-6xl mx-auto px-4">
          <h2 class="text-4xl font-extrabold text-gray-900">Advanced EMI Calculator</h2>
          <p class="text-base text-gray-600 mt-2">Enter loan details to see EMI, interest, payment breakdown, and amortization schedule.</p>

          <div class="mt-8 grid lg:grid-cols-2 gap-8">
              <!-- INPUT CARD -->
              <div class="bg-white border rounded-3xl p-6 shadow-soft transition-all duration-300 hover:shadow-xl">
                  <form id="emiForm" class="grid gap-6">
                      <!-- Loan Amount -->
                      <div>
                          <label for="amt" class="block text-sm font-medium text-gray-700">Loan Amount (₹)</label>
                          <div class="mt-1 flex gap-3">
                              <input id="amt" name="amt" type="number" min="1000" step="1000" value="500000" class="w-full rounded-xl border p-3 focus:ring-slate-900" />
                              <button type="button" id="amtClear" title="Reset Amount" class="px-4 rounded-xl bg-gray-100 hover:bg-gray-200 transition">Reset</button>
                          </div>
                          <input id="amtRange" type="range" min="10000" max="20000000" step="1000" value="500000" class="w-full mt-3" />
                      </div>

                      <!-- Interest Rate -->
                      <div>
                          <label for="roi" class="block text-sm font-medium text-gray-700">Interest (p.a. %)</label>
                          <div class="mt-1 grid grid-cols-2 gap-3">
                              <input id="roi" name="roi" type="number" min="0.1" step="0.01" value="11.5" class="rounded-xl border p-3 focus:ring-slate-900" />
                              <div>
                                  <input id="roiRange" type="range" min="0.5" max="25" step="0.01" value="11.5" class="w-full" />
                                  <div class="text-xs text-gray-500 mt-1">Adjust slider to explore rates quickly.</div>
                              </div>
                          </div>
                      </div>

                      <!-- Tenure -->
                      <div>
                          <label for="ten" class="block text-sm font-medium text-gray-700">Tenure</label>
                          <div class="mt-1 grid grid-cols-2 gap-3">
                              <input id="ten" name="ten" type="number" min="1" step="1" value="36" class="rounded-xl border p-3 focus:ring-slate-900" />
                              <select id="tenUnit" class="rounded-xl border p-3">
                                  <option value="months" selected>Months</option>
                                  <option value="years">Years</option>
                              </select>
                          </div>
                          <input id="tenRange" type="range" min="1" max="360" step="1" value="36" class="w-full mt-3" />
                      </div>

                      <!-- Extra prepayment input -->
                      <div>
                          <label for="prepay" class="block text-sm font-medium text-gray-700">Extra monthly prepayment (optional, ₹)</label>
                          <input id="prepay" type="number" min="0" step="100" value="0" class="w-full mt-1 rounded-xl border p-3 focus:ring-slate-900" />
                      </div>

                      <!-- Buttons -->
                      <div class="flex gap-4 pt-4">
                          <button id="calcBtn" type="button" class="flex-1 rounded-xl bg-slate-900 text-white py-3 font-semibold hover:bg-slate-700 transition duration-200">Calculate EMI</button>
                          <button id="resetBtn" type="button" class="flex-1 rounded-xl border border-gray-300 text-gray-700 py-3 font-semibold hover:bg-gray-50 transition duration-200">Reset All</button>
                      </div>

                      <div id="errorMsg" class="text-sm text-red-600 hidden p-2 border border-red-200 bg-red-50 rounded-lg"></div>
                  </form>
              </div>

              <!-- OUTPUT CARD -->
              <div class="bg-white border rounded-3xl p-6 shadow-soft transition-all duration-300 hover:shadow-xl flex flex-col">
                  <h4 class="font-bold text-xl text-gray-800">Summary & Analysis</h4>

                  <div class="mt-4 grid sm:grid-cols-3 gap-4 text-center">
                      <div class="p-4 border rounded-xl bg-slate-50">
                          <div class="text-sm text-gray-600">Monthly Payment</div>
                          <div id="emiOut" class="text-3xl font-extrabold mt-1 text-slate-900">—</div>
                          <div class="text-xs text-gray-500 mt-1">EMI + Prepayment</div>
                      </div>
                      <div class="p-4 border rounded-xl">
                          <div class="text-sm text-gray-600">Total Interest Paid</div>
                          <div id="intOut" class="text-2xl font-extrabold mt-1 text-red-600">—</div>
                      </div>
                      <div class="p-4 border rounded-xl">
                          <div class="text-sm text-gray-600">Total Outflow</div>
                          <div id="totOut" class="text-2xl font-extrabold mt-1 text-green-700">—</div>
                      </div>
                  </div>

                  <div class="mt-5 flex justify-center">
                      <div class="w-64">
                          <canvas id="emiChart"></canvas>
                      </div>

                  </div>

                  <div class="mt-6 flex gap-3">
                      <button id="showSchedule" class="rounded-xl border py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">Show Schedule</button>
                      <button id="downloadCSV" class="rounded-xl border py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-100 transition ml-auto">Download CSV</button>
                  </div>

                  <p class="text-xs text-gray-500 mt-4">*Illustrative. Actuals vary by bank, profile, and documentation.</p>
              </div>
          </div>

          <!-- Amortization modal / section -->
          <div id="scheduleWrap" class="mt-8 bg-white border rounded-3xl p-6 shadow-soft transition-all duration-300 hover:shadow-xl hidden">
              <div class="flex items-center justify-between border-b pb-3">
                  <h3 class="text-xl font-semibold text-gray-800">Detailed Amortization Schedule</h3>
                  <button id="closeSchedule" class="text-sm text-slate-600 hover:text-slate-900 transition font-medium">Close (X)</button>
              </div>
              <div class="mt-4 overflow-auto max-h-[500px]">
                  <table class="min-w-full text-sm mini-nowrap border-collapse">
                      <thead class="text-left text-gray-600 sticky top-0 bg-white">
                      <tr>
                          <th class="px-3 py-2">#</th>
                          <th class="px-3 py-2">Date</th>
                          <th class="px-3 py-2 text-right">Payment (₹)</th>
                          <th class="px-3 py-2 text-right">Principal (₹)</th>
                          <th class="px-3 py-2 text-right">Interest (₹)</th>
                          <th class="px-3 py-2 text-right">Prepay (₹)</th>
                          <th class="px-3 py-2 text-right">Balance (₹)</th>
                      </tr>
                      </thead>
                      <tbody id="scheduleBody"></tbody>
                  </table>
              </div>
          </div>

      </div>
  </section>


  <!-- Partner CTA -->
  <section id="partner" class="py-16">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h2 class="text-3xl font-extrabold">Become Our Partner </h2>
        <p class="mt-3 text-gray-700">Earn industry‑leading payouts by sourcing loan leads. Get training, CRM access and dedicated support.</p>
        <ul class="mt-4 space-y-2 text-gray-700">
          <li>• Payouts on disbursal</li>
          <li>• Real‑time lead tracking</li>
          <li>• Marketing toolkit</li>
        </ul>
        <a href="#apply" class="mt-6 inline-block px-5 py-3 rounded-xl bg-primary-600 text-white">Join Now</a>
      </div>
      <div class="bg-white border rounded-2xl p-6 shadow-soft">
        <h3 class="text-xl font-bold">Quick Partner Form</h3>
        <form id="partnerForm" class="grid grid-cols-1 gap-3" method="POST" action="send.php" novalidate>
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES); ?>">
          <input type="hidden" name="form_type" value="partner">
          <label class="hidden-hp">Leave this field empty <input type="text" name="website" autocomplete="off"></label>
          <div class="grid md:grid-cols-2 gap-3">
            <input required name="pname" class="w-full rounded-xl border p-3" placeholder="Full Name" />
            <input required name="pphone" pattern="^[6-9][0-9]{9}$" class="w-full rounded-xl border p-3" placeholder="Mobile" />
          </div>
          <input name="pcity" class="w-full rounded-xl border p-3" placeholder="City" />
          <input name="pexp" class="w-full rounded-xl border p-3" placeholder="Experience (years)" />
          <button class="mt-1 rounded-xl bg-gray-900 text-white py-3 font-semibold">Submit</button>
          <?php if (!empty($_GET['status']) && $_GET['status']==='ok' && !empty($_GET['f']) && $_GET['f']==='partner'): ?>
            <p class="text-sm text-green-700">Thanks! Our team will contact you.</p>
          <?php elseif (!empty($_GET['status']) && $_GET['status']==='error' && !empty($_GET['f']) && $_GET['f']==='partner'): ?>
            <p class="text-sm text-red-700">Sorry, something went wrong. Please try again.</p>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="py-16 bg-white border-y">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-extrabold mb-8">What customers say</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <figure class="bg-white border rounded-2xl p-6 shadow-soft">
          <blockquote class="text-gray-700">“Smooth process and quick disbursal. Got a better rate than my bank.”</blockquote>
          <figcaption class="mt-4 text-sm text-gray-600">— Mayank Sharma., Vaishali Nagar</figcaption>
        </figure>
        <figure class="bg-white border rounded-2xl p-6 shadow-soft">
          <blockquote class="text-gray-700">“Fincrade handled documentation end‑to‑end. Highly recommended.”</blockquote>
          <figcaption class="mt-4 text-sm text-gray-600">— Manish K., Jaipur</figcaption>
        </figure>
        <figure class="bg-white border rounded-2xl p-6 shadow-soft">
          <blockquote class="text-gray-700">“As a partner, their CRM & payouts are excellent.”</blockquote>
          <figcaption class="mt-4 text-sm text-gray-600">— Sonu Gupta., Jaipur</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq" class="py-16">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-extrabold mb-6">Frequently Asked Questions</h2>
      <div class="space-y-4" id="faqWrap">
        <details class="group bg-white border rounded-2xl p-5 shadow-soft">
          <summary class="cursor-pointer font-semibold">How soon can I get a loan after applying?</summary>
          <div class="mt-2 text-gray-700">For personal loans, same‑day approvals are common with complete documents. Disbursal can be 24–72 hours depending on the lender and profile.</div>
        </details>
        <details class="group bg-white border rounded-2xl p-5 shadow-soft">
          <summary class="cursor-pointer font-semibold">Do you charge any fees?</summary>
          <div class="mt-2 text-gray-700">Fincrade does not charge upfront fees. Certain cases may involve processing/third‑party charges by lenders, disclosed transparently.</div>
        </details>
        <details class="group bg-white border rounded-2xl p-5 shadow-soft">
          <summary class="cursor-pointer font-semibold">Will my credit score be impacted?</summary>
          <div class="mt-2 text-gray-700">We begin with a soft check/eligibility screening. Hard pulls happen only when you consent to proceed with a specific lender.</div>
        </details>
      </div>
      <script type="application/ld+json">
      {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
          {"@type":"Question","name":"How soon can I get a loan after applying?","acceptedAnswer":{"@type":"Answer","text":"For personal loans, same‑day approvals are common with complete documents. Disbursal can be 24–72 hours depending on the lender and profile."}},
          {"@type":"Question","name":"Do you charge any fees?","acceptedAnswer":{"@type":"Answer","text":"Fincrade does not charge upfront fees. Certain cases may involve processing/third‑party charges by lenders, disclosed transparently."}},
          {"@type":"Question","name":"Will my credit score be impacted?","acceptedAnswer":{"@type":"Answer","text":"We begin with a soft check/eligibility screening. Hard pulls happen only when you consent to proceed with a specific lender."}}
        ]
      }
      </script>
    </div>
  </section>

  <!-- Contact / Apply -->
  <section id="apply" class="py-16 bg-primary-900 text-white">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <h2 class="text-3xl font-extrabold">Apply with Fincrade</h2>
        <p class="mt-3 text-primary-100">Tell us a few details and our advisor will call you within 1 business day.</p>
        <div class="mt-6 grid grid-cols-2 gap-3 text-primary-100 text-sm">
          <div class="p-4 rounded-xl bg-white/5 border border-white/10">RBI‑regulated partners</div>
          <div class="p-4 rounded-xl bg-white/5 border border-white/10">Secure & encrypted</div>
          <div class="p-4 rounded-xl bg-white/5 border border-white/10">Pan‑India service</div>
          <div class="p-4 rounded-xl bg-white/5 border border-white/10">Dedicated advisor</div>
        </div>
      </div>
      <div class="bg-white/10 rounded-2xl p-6 md:p-8 border border-white/20">
        <form id="applyForm" class="grid grid-cols-1 gap-3" method="POST" action="send.php" novalidate>
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES); ?>">
          <input type="hidden" name="form_type" value="apply">
          <label class="hidden-hp">Leave this field empty <input type="text" name="website" autocomplete="off"></label>
          <div class="grid md:grid-cols-2 gap-3">
            <input required name="aname" class="w-full rounded-xl border border-white/20 bg-white/80 text-gray-900 p-3" placeholder="Full Name" />
            <input required name="aphone" pattern="^[6-9][0-9]{9}$" class="w-full rounded-xl border border-white/20 bg-white/80 text-gray-900 p-3" placeholder="Mobile (10 digits)" />
          </div>
          <div class="grid md:grid-cols-2 gap-3">
            <input type="email" name="aemail" class="w-full rounded-xl border border-white/20 bg-white/80 text-gray-900 p-3" placeholder="Email" />
            <select name="aproduct" required class="w-full rounded-xl border border-white/20 bg-white/80 text-gray-900 p-3">
              <option value="" disabled selected>Select Product</option>
              <option>Personal Loan</option>
              <option>Business Loan</option>
              <option>Home Loan</option>
              <option>Loan Against Property</option>
              <option>Car Loan</option>
              <option>Credit Card</option>
              <option>Gold Loan</option>
            </select>
          </div>
          <textarea name="anote" rows="3" class="w-full rounded-xl border border-white/20 bg-white/80 text-gray-900 p-3" placeholder="Notes (optional)"></textarea>
          <button class="mt-1 rounded-xl bg-white text-gray-900 py-3 font-semibold">Submit Application</button>
          <?php if (!empty($_GET['status']) && $_GET['status']==='ok' && !empty($_GET['f']) && $_GET['f']==='apply'): ?>
            <p class="text-sm text-green-200">Submitted! We'll contact you shortly.</p>
          <?php elseif (!empty($_GET['status']) && $_GET['status']==='error' && !empty($_GET['f']) && $_GET['f']==='apply'): ?>
            <p class="text-sm text-red-200">Sorry, something went wrong. Please try again.</p>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </section>

  <!-- Contact Details -->
  <section id="contact" class="py-12">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-6">
      <div class="bg-white border rounded-2xl p-6 shadow-soft">
        <h4 class="font-bold">Head Office</h4>
        <p class="mt-2 text-gray-700">Tripura Venture<br/>B 148 Mahal Yojna Jaipur, Rajasthan, India 302017</p>
      </div>
      <div class="bg-white border rounded-2xl p-6 shadow-soft">
        <h4 class="font-bold">Support</h4>
        <p class="mt-2 text-gray-700">support@fincrade.com<br/>+91 82093 12454</p>
      </div>
      <div class="bg-white border rounded-2xl p-6 shadow-soft">
        <h4 class="font-bold">Hours</h4>
        <p class="mt-2 text-gray-700">Mon–Sat: 10:00–18:00 IST</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-4 gap-6 text-sm">
      <div>
        <div class="flex items-center gap-2">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="text-primary-700"><path d="M12 3l8.66 5v8l-8.66 5L3.34 16V8L12 3z" stroke="currentColor"/></svg>
          <span class="font-extrabold text-lg">Fincrade</span>
        </div>
        <p class="mt-3 text-gray-600">© <span id="year"></span> Tripura Venture All rights reserved.</p>
      </div>
      <div>
        <h5 class="font-bold">Products</h5>
        <ul class="mt-2 space-y-1 text-gray-700">
          <li><a href="#products">Personal Loan</a></li>
          <li><a href="#products">Business Loan</a></li>
          <li><a href="#products">Home Loan</a></li>
          <li><a href="#products">Credit Cards</a></li>
          <li><a href="#products">Gold Loan</a></li>
        </ul>
      </div>
      <div>
        <h5 class="font-bold">Company</h5>
        <ul class="mt-2 space-y-1 text-gray-700">
          <li><a href="#contact">Contact</a></li>
          <li><a href="#faq">FAQ</a></li>
          <li><a href="#partner">Partner</a></li>
          <li><a href="#">Terms & Privacy</a></li>
        </ul>
      </div>
      <div>
        <h5 class="font-bold">Follow</h5>
        <ul class="mt-2 space-y-1 text-gray-700">
          <li><a href="#">Instagram</a></li>
          <li><a href="#">LinkedIn</a></li>
          <li><a href="#">Facebook</a></li>
        </ul>
      </div>
    </div>
  </footer>

  <!-- Floating WhatsApp -->
  <a href="https://wa.me/8764100245" class="fixed bottom-6 right-6 grid place-items-center w-14 h-14 rounded-full bg-green-500 text-white shadow-lg" aria-label="WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path d="M20.52 3.48A11.91 11.91 0 0 0 12.05 0C5.64 0 .45 5.17.45 11.55c0 2.03.54 4.01 1.57 5.76L0 24l6.86-1.96a11.46 11.46 0 0 0 5.18 1.24h.01c6.41 0 11.6-5.17 11.6-11.55 0-3.09-1.21-6-3.43-8.25zM12.05 21.1h-.01a9.6 9.6 0 0 1-4.91-1.34l-.35-.2-4.06 1.16 1.09-3.96-.22-.36A9.37 9.37 0 0 1 2.36 11.6c0-5.14 4.21-9.33 9.69-9.33 2.59 0 5.02 1 6.85 2.82a9.64 9.64 0 0 1 2.84 6.86c0 5.14-4.21 9.33-9.69 9.33zm5.57-7.01c-.31-.16-1.86-.91-2.15-1.02-.29-.11-.5-.16-.71.16-.21.31-.81 1.02-.99 1.23-.18.21-.37.24-.68.08-.31-.16-1.32-.48-2.52-1.53-.93-.79-1.56-1.77-1.74-2.07-.18-.31-.02-.48.14-.64.14-.14.31-.37.45-.56.15-.19.2-.32.29-.53.1-.21.05-.4-.02-.56-.08-.16-.71-1.71-.97-2.35-.26-.63-.52-.54-.71-.55h-.6c-.21 0-.56.08-.85.39-.29.31-1.12 1.1-1.12 2.68 0 1.58 1.15 3.1 1.31 3.31.16.21 2.26 3.6 5.47 5.06.76.33 1.35.53 1.81.68.76.24 1.45.21 2 .13.61-.09 1.86-.76 2.12-1.49.26-.73.26-1.35.18-1.49-.07-.14-.27-.22-.58-.37z"/></svg>
  </a>

  <!-- Scripts -->
  <script>
    // Mobile nav toggle
    document.getElementById('mobileMenuBtn').addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // Year
    document.getElementById('year').textContent = new Date().getFullYear();

    // EMI calculator
    //
    // Old EMI calculator JS

    // function calculateEMI(P, annualRate, months){
    //   const r = (annualRate/12)/100; // monthly interest
    //   if(r === 0) return {emi: P/months, totalInterest: 0, total: P};
    //   const pow = Math.pow(1+r, months);
    //   const emi = P * r * (pow / (pow - 1));
    //   const total = emi * months;
    //   return {emi, totalInterest: total - P, total};
    // }
    // function formatINR(x){
    //   return '₹' + x.toLocaleString('en-IN', {maximumFractionDigits:0});
    // }
    // function runCalc(){
    //   const P = Number(document.getElementById('amt').value || 0);
    //   const R = Number(document.getElementById('roi').value || 0);
    //   const N = Number(document.getElementById('ten').value || 0);
    //   const {emi, totalInterest, total} = calculateEMI(P,R,N);
    //   document.getElementById('emiOut').textContent = isFinite(emi) ? formatINR(emi) : '—';
    //   document.getElementById('intOut').textContent = isFinite(totalInterest) ? formatINR(totalInterest) : '—';
    //   document.getElementById('totOut').textContent = isFinite(total) ? formatINR(total) : '—';
    // }
    // document.getElementById('calcBtn').addEventListener('click', (e)=>{ e.preventDefault(); runCalc(); });
    // // initial calc
    // runCalc();

  //   added JS for New Calculator

  //   ...............................
    (function() {
        // Constants and DOM elements
        const D = (id) => document.getElementById(id);
        const amtInput = D('amt');
        const amtRange = D('amtRange');
        const roiInput = D('roi');
        const roiRange = D('roiRange');
        const tenInput = D('ten');
        const tenUnitSelect = D('tenUnit');
        const tenRange = D('tenRange');
        const prepayInput = D('prepay');
        const emiOut = D('emiOut');
        const intOut = D('intOut');
        const totOut = D('totOut');
        const errorMsg = D('errorMsg');
        const scheduleBody = D('scheduleBody');
        const scheduleWrap = D('scheduleWrap');
        const calcBtn = D('calcBtn');
        const resetBtn = D('resetBtn');

        // Chart instance
        let emiChart;

        // --- Utility Functions ---

        /**
         * Formats a number as an Indian Rupee string.
         * @param {number} num
         * @returns {string}
         */
        const formatCurrency = (num) => {
            if (typeof num !== 'number' || isNaN(num)) return '—';
            return num.toLocaleString('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0
            }).replace('₹', '₹ ');
        };

        /**
         * Converts months to years or vice versa, ensuring the maximum value is respected.
         * @param {number} value
         * @param {string} unit 'months' or 'years'
         * @returns {number}
         */
        const convertTenure = (value, unit) => {
            const maxMonths = 360;
            if (unit === 'years') {
                return Math.min(Math.round(value * 12), maxMonths);
            }
            return Math.min(value, maxMonths);
        };

        /**
         * Calculates the Equated Monthly Installment (EMI).
         * M = P * R * (1 + R)^N / ((1 + R)^N - 1)
         * @param {number} P Principal Loan Amount
         * @param {number} R Monthly Interest Rate (decimal)
         * @param {number} N Loan Tenure in Months
         * @returns {number} EMI
         */
        const calculateEMI = (P, R, N) => {
            if (R === 0) {
                return P / N; // Simple calculation if interest is zero
            }
            const power = Math.pow(1 + R, N);
            return P * R * power / (power - 1);
        };

        // --- Core Calculation Logic ---

        /**
         * Calculates the amortization schedule, handling prepayments.
         * @param {number} P Principal
         * @param {number} R Monthly Interest Rate (decimal)
         * @param {number} N Total Months
         * @param {number} prepay Monthly Prepayment
         * @returns {{emi: number, totalInterest: number, schedule: Array}}
         */
        const calculateAmortization = (P, R, N, prepay) => {
            let principal = P;
            let totalInterest = 0;
            let schedule = [];
            let month = 1;
            let originalEMI = calculateEMI(P, R, N);
            let calculatedEMI = originalEMI;

            // Cap EMI at P + (P*R) to avoid infinite loop with extremely low N/high R
            if (calculatedEMI > P * 2) {
                calculatedEMI = P * 0.05; // Use a reasonable default for stability
            }

            while (principal > 0 && month <= N * 5) { // Safety limit: 5x original tenure
                const interestPayment = principal * R;
                const regularPrincipalPayment = calculatedEMI - interestPayment;

                let totalPayment = calculatedEMI + prepay;
                let actualPrincipalPayment = regularPrincipalPayment;
                let actualEMI = calculatedEMI;
                let monthPrepay = prepay;

                if (principal - regularPrincipalPayment <= 0) {
                    // Last payment scenario (no prepayment)
                    actualEMI = principal + interestPayment;
                    actualPrincipalPayment = principal;
                    totalPayment = actualEMI;
                    monthPrepay = 0;
                } else if (principal - (regularPrincipalPayment + prepay) <= 0 && prepay > 0) {
                    // Last payment scenario (with prepayment)
                    monthPrepay = principal - regularPrincipalPayment; // Only pay the remaining principal
                    actualPrincipalPayment = regularPrincipalPayment + monthPrepay;
                    totalPayment = actualEMI + monthPrepay;
                } else if (prepay > 0) {
                    // Regular payment with prepayment
                    actualPrincipalPayment = regularPrincipalPayment + prepay;
                }


                // Final check on remaining principal after payment
                if (principal <= 0) break; // Should not happen here, but for safety

                let closingBalance = principal - actualPrincipalPayment;

                // Handle the case where the payment overshoots the principal
                if (closingBalance < 0) {
                    const excess = -closingBalance;
                    actualPrincipalPayment += excess; // Reduce principal paid
                    closingBalance = 0;
                }

                // Ensure closing balance is not negative due to floating point
                if (closingBalance < 0.01 && closingBalance > -0.01) {
                    closingBalance = 0;
                }


                totalInterest += interestPayment;

                // Generate a simple date (Month, Year)
                const paymentDate = new Date();
                paymentDate.setMonth(paymentDate.getMonth() + month - 1);


                schedule.push({
                    month: month,
                    date: paymentDate.toLocaleDateString('en-IN', { year: 'numeric', month: 'short' }),
                    payment: actualEMI + monthPrepay, // Total outflow
                    principal: actualPrincipalPayment,
                    interest: interestPayment,
                    prepay: monthPrepay,
                    balance: closingBalance
                });

                principal = closingBalance;
                month++;

                // Break if an extremely long tenure is calculated (e.g., > 30 years)
                if (month > N + 12 * 5) break;
            }

            // Final total interest adjustment, as the loop might cut short the very last interest
            if (schedule.length > 0 && schedule[schedule.length - 1].balance > 0) {
                // Recalculate if still pending (should be rare)
                return calculateAmortization(P, R, N + 1, prepay); // Try with one more month
            }

            // Final total interest is the sum of interest from the schedule
            const finalTotalInterest = schedule.reduce((sum, item) => sum + item.interest, 0);

            return {
                emi: originalEMI, // Display the calculated EMI without prepayment
                monthlyPayment: schedule.length > 0 ? schedule[0].payment : 0, // Display EMI + Prepay
                totalInterest: finalTotalInterest,
                schedule: schedule
            };
        };

        /**
         * Executes the calculation, updates the UI, chart, and schedule.
         */
        const runCalculation = () => {
            // 1. Get and Validate Inputs
            const loanAmount = parseFloat(amtInput.value);
            const annualRate = parseFloat(roiInput.value);
            let tenureMonths = tenUnitSelect.value === 'years'
                ? parseFloat(tenInput.value) * 12
                : parseFloat(tenInput.value);
            const prepayment = parseFloat(prepayInput.value) || 0;

            errorMsg.classList.add('hidden');
            errorMsg.textContent = '';

            if (isNaN(loanAmount) || loanAmount <= 0) {
                errorMsg.textContent = 'Please enter a valid Loan Amount.';
                errorMsg.classList.remove('hidden');
                return;
            }
            if (isNaN(annualRate) || annualRate < 0) {
                errorMsg.textContent = 'Please enter a valid Interest Rate.';
                errorMsg.classList.remove('hidden');
                return;
            }
            if (isNaN(tenureMonths) || tenureMonths <= 0) {
                errorMsg.textContent = 'Please enter a valid Loan Tenure.';
                errorMsg.classList.remove('hidden');
                return;
            }
            if (isNaN(prepayment) || prepayment < 0) {
                errorMsg.textContent = 'Please enter a valid Prepayment amount (or 0).';
                errorMsg.classList.remove('hidden');
                return;
            }

            // 2. Perform Calculations
            const monthlyRate = annualRate / (12 * 100);

            const { monthlyPayment, totalInterest, schedule } = calculateAmortization(
                loanAmount,
                monthlyRate,
                tenureMonths,
                prepayment
            );

            const totalPrincipal = loanAmount;
            const totalOutflow = totalPrincipal + totalInterest;

            // 3. Update Output Summary
            emiOut.textContent = formatCurrency(monthlyPayment);
            intOut.textContent = formatCurrency(totalInterest);
            totOut.textContent = formatCurrency(totalOutflow);

            // 4. Update Chart
            updateChart(totalPrincipal, totalInterest);

            // 5. Update Amortization Schedule
            updateScheduleTable(schedule);
        };

        // --- Chart Management ---

        /**
         * Initializes the EMI Chart.
         */
        const initializeChart = () => {
            const ctx = D('emiChart').getContext('2d');
            emiChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Principal Amount', 'Total Interest'],
                    datasets: [{
                        data: [500000, 0], // Initial dummy data
                        backgroundColor: ['#1e293b', '#64748b'], // slate-900, slate-500
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                boxWidth: 12
                            }
                        },
                        title: {
                            display: true,
                            text: 'Principal vs. Interest Breakdown',
                            font: { size: 16 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = formatCurrency(context.parsed);
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        };

        /**
         * Updates the chart with new principal and interest data.
         * @param {number} principal
         * @param {number} interest
         */
        const updateChart = (principal, interest) => {
            if (!emiChart) {
                initializeChart();
            }
            emiChart.data.datasets[0].data = [principal, interest];
            emiChart.update();
        };


        // --- Schedule Management ---

        /**
         * Populates the amortization table.
         * @param {Array} schedule
         */
        const updateScheduleTable = (schedule) => {
            scheduleBody.innerHTML = '';
            const frag = document.createDocumentFragment();

            schedule.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                    <td class="px-3 py-2">${item.month}</td>
                    <td class="px-3 py-2">${item.date}</td>
                    <td class="px-3 py-2 text-right">${item.payment.toLocaleString('en-IN', { maximumFractionDigits: 0 })}</td>
                    <td class="px-3 py-2 text-right">${item.principal.toLocaleString('en-IN', { maximumFractionDigits: 0 })}</td>
                    <td class="px-3 py-2 text-right">${item.interest.toLocaleString('en-IN', { maximumFractionDigits: 0 })}</td>
                    <td class="px-3 py-2 text-right">${item.prepay.toLocaleString('en-IN', { maximumFractionDigits: 0 })}</td>
                    <td class="px-3 py-2 text-right font-medium">${item.balance.toLocaleString('en-IN', { maximumFractionDigits: 0 })}</td>
                `;
                frag.appendChild(row);
            });

            scheduleBody.appendChild(frag);
        };

        /**
         * Generates a CSV string from the schedule data.
         * @param {Array} schedule
         * @returns {string}
         */
        const generateCSV = (schedule) => {
            let csv = "Month,Payment Date,Total Payment (₹),Principal Paid (₹),Interest Paid (₹),Prepayment (₹),Outstanding Balance (₹)\n";
            schedule.forEach(item => {
                csv += [
                    item.month,
                    item.date,
                    item.payment.toFixed(2),
                    item.principal.toFixed(2),
                    item.interest.toFixed(2),
                    item.prepay.toFixed(2),
                    item.balance.toFixed(2)
                ].join(',') + "\n";
            });
            return csv;
        };

        // --- Event Listeners and Initializers ---

        // Sync input field to range slider (and vice versa)
        const syncInputs = (input, range) => {
            input.addEventListener('input', () => {
                range.value = input.value;
                runCalculation();
            });
            range.addEventListener('input', () => {
                input.value = range.value;
                runCalculation();
            });
        };

        const setupListeners = () => {
            // Sync number inputs and range sliders
            syncInputs(amtInput, amtRange);
            syncInputs(roiInput, roiRange);
            syncInputs(tenInput, tenRange);

            // Prepayment input change
            prepayInput.addEventListener('input', runCalculation);

            // Tenure Unit change (Months <-> Years)
            tenUnitSelect.addEventListener('change', () => {
                let months = tenUnitSelect.value === 'years'
                    ? parseFloat(tenInput.value) * 12
                    : parseFloat(tenInput.value) / 12;

                tenInput.value = tenUnitSelect.value === 'years'
                    ? Math.ceil(months) // Show years as a rounded number
                    : Math.ceil(months * 12); // Show months as a rounded number

                // Update range maximums and current value
                if (tenUnitSelect.value === 'years') {
                    tenInput.min = 1;
                    tenInput.max = 30;
                    tenRange.min = 1;
                    tenRange.max = 30;
                    tenInput.value = Math.round(tenRange.value / 12);
                    tenRange.value = tenInput.value;
                } else {
                    tenInput.min = 1;
                    tenInput.max = 360;
                    tenRange.min = 1;
                    tenRange.max = 360;
                    tenInput.value = Math.round(tenRange.value);
                    tenRange.value = tenInput.value;
                }

                // Important: Recalculate based on the new tenure value
                runCalculation();
            });

            // Calculate button
            calcBtn.addEventListener('click', runCalculation);

            // Reset button
            resetBtn.addEventListener('click', () => {
                // Reset to initial values
                amtInput.value = 500000;
                amtRange.value = 500000;
                roiInput.value = 11.5;
                roiRange.value = 11.5;
                tenInput.value = 36;
                tenRange.value = 36;
                tenUnitSelect.value = 'months';
                prepayInput.value = 0;
                errorMsg.classList.add('hidden');
                scheduleWrap.classList.add('hidden');
                runCalculation();
            });

            // Reset Amount button
            D('amtClear').addEventListener('click', () => {
                amtInput.value = 500000;
                amtRange.value = 500000;
                runCalculation();
            });

            // Show Schedule button
            D('showSchedule').addEventListener('click', () => {
                scheduleWrap.classList.toggle('hidden');
            });

            // Close Schedule button
            D('closeSchedule').addEventListener('click', () => {
                scheduleWrap.classList.add('hidden');
            });

            // Download CSV
            D('downloadCSV').addEventListener('click', () => {
                const data = getScheduleData();
                // NOTE: Using a custom modal/message box instead of alert()
                if (data.length === 0) {
                    // Simple console log for non-alert error indication
                    console.error("Calculation required before downloading CSV.");
                    return;
                }

                const csvContent = generateCSV(data);
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', 'emi_amortization_schedule.csv');
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            // Helper to retrieve the current schedule data from the table (or rerun calc)
            const getScheduleData = () => {
                // Re-run the calculation to ensure schedule is fresh
                const loanAmount = parseFloat(amtInput.value);
                const annualRate = parseFloat(roiInput.value);
                let tenureMonths = tenUnitSelect.value === 'years'
                    ? parseFloat(tenInput.value) * 12
                    : parseFloat(tenInput.value);
                const prepayment = parseFloat(prepayInput.value) || 0;

                const monthlyRate = annualRate / (12 * 100);
                const { schedule } = calculateAmortization(
                    loanAmount,
                    monthlyRate,
                    tenureMonths,
                    prepayment
                );
                return schedule;
            }

        };

        // Run on page load
        window.onload = function() {
            initializeChart();
            setupListeners();
            // Initial calculation to populate fields and chart
            runCalculation();
        };
    })(); // IIFE ends here


  </script>


</body>
</html>
