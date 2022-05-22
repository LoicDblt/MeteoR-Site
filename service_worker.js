const NOM_CACHE = "offline"
const PAGE = "hors_ligne.html"
const STYLE = "style/erreur.css"
const FOND = "img/erreur_404.jpg"
const FAVICON = "img/meteor_favicon.webp"

self.addEventListener("install", (event) =>
{
	event.waitUntil(
		caches.open(NOM_CACHE).then(function(cache)
		{
			return cache.addAll(
				[
					PAGE,
					STYLE,
					FOND,
					FAVICON
				]
			);
		})
	);
});

self.addEventListener("activate", (event) =>
{
	event.waitUntil(
		(async () => {
			if ("navigationPreload" in self.registration)
			{
				await self.registration.navigationPreload.enable();
			}
		})()
	);
	self.clients.claim();
});

self.addEventListener("fetch", (event) =>
{
	if (event.request.mode === "navigate")
	{
		event.respondWith(
			(async () =>
			{
				try
				{
					const precharger = await event.preloadResponse
					if (precharger)
					{
						return precharger;
					}

					const reponseReseau = await fetch(event.request);
					return reponseReseau;
				}
				catch
				{
					fetch(event.request).catch(function()
					{
						return caches.match(event.request);
					})
					const cache = await caches.open(NOM_CACHE);
					const reponseCache = await cache.match(PAGE);
					return reponseCache;
				}
			})()
		);
	}
});