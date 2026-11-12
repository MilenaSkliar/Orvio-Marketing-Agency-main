window.SITE_CONFIG = {
  "companyName": "Orvio",
  "siteTitle": "Orvio — Google & Meta Marketing",
  "email": "hello@orvio.example",
  "address": "Working worldwide. Based online.",
  "logo": "assets/logo.svg",
  "favicon": "assets/logo.svg",
  "footer": "We bring strategy, creative and Google & Meta advertising together. Thoughtful work, open conversations and a clear direction for your next chapter.",
  "disclaimer": "Orvio is an independent marketing agency and is not affiliated with, endorsed by, or an official partner of Google or Meta. All trademarks belong to their respective owners. Campaign outcomes depend on your market, budget and offer; results are not guaranteed. This preview uses illustrative team imagery and sample review layouts.",
  "collaborateTitle": "Advertise & Collaborate",
  "collaborateText": "We are always open to new opportunities, high-impact collaborations, and tailored business partnerships. Whether you want to advertise your brand to our audience, launch a joint project, or book our professional services, we are ready to bring your ideas to life. Every business is unique, and we don't believe in one-size-fits-all solutions. Please reach out to us using the contact form below, tell us a bit about your goals, and our team will get back to you with an exclusive, custom-tailored proposal designed strictly for your budget and objectives. Let’s build something great together.",
  "form": {
    "endpoint": "contact.php",
    "enabled": true,
    "subject": "New website enquiry",
    "successMessage": "Thank you! We have successfully received your request. Our team will review your information and get back to you shortly."
  },
  "links": {
    "google": "https://ads.google.com/",
    "meta": "https://www.facebook.com/business/",
    "analytics": "https://analytics.google.com/"
  },
  "routes": {
    "home": "index.html",
    "about": "index.html#about",
    "team": "index.html#team",
    "pricing": "index.html#pricing",
    "reviews": "index.html#reviews",
    "contact": "index.html#contact",
    "googleService": "google-ads.html",
    "metaService": "meta-ads.html",
    "strategyService": "strategy.html",
    "privacy": "privacy.html",
    "terms": "terms.html",
    "cookies": "cookies.html"
  }
};
window.siteConfigReady = Promise.resolve(window.SITE_CONFIG);
