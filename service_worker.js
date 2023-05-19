/*
	Copyright 2015, 2019 Google Inc. All Rights Reserved.
	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at
	http://www.apache.org/licenses/LICENSE-2.0
	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.
*/

const NOM_CACHE = "MeteoR_PWA";
const FICHIERS_EN_CACHE = [
	"hors_ligne.html",
	"style/erreur.css",
	"img/erreur.jpg",
	"img/icons/favicon.webp",
	"img/icons/apple_touch.webp"
];

self.addEventListener("install", (event) => {
	event.waitUntil(
		caches.open(NOM_CACHE).then((cache) => {
			return cache.addAll(FICHIERS_EN_CACHE);
		})
	);
});

self.addEventListener("activate", (event) => {
	event.waitUntil(
		(async () => {
			// Enable navigation preload if it's supported
			if ("navigationPreload" in self.registration)
			{
				await self.registration.navigationPreload.enable();
			}
		})()
	);

	// Tell the active service worker to take control of the page immediately.
	self.clients.claim();
});

self.addEventListener("fetch", (event) => {
	console.log(event);
	// We only want to call event.respondWith() if this is a navigation request
	// for an HTML page
	if (event.request.mode === "navigate"){
		event.respondWith(
			(async () => {
				// First, try to use the navigation preload response if it's
				// supported
				try{
					const precharger = await event.preloadResponse;
					if (precharger)
						return precharger;

					const reponseReseau = await fetch(event.request);
					return reponseReseau;
				}
				catch{
					// "Catch" is only triggered if an exception is thrown,
					// which is likely due to a network error.
					// If fetch() returns a valid HTTP response with a response
					// code in the 4xx or 5xx range, the catch() will NOT be
					// called
					fetch(event.request).catch(() => {
						return caches.match(event.request);
					});
					const cache = await caches.open(NOM_CACHE);
					const reponseCache = await cache
						.match(FICHIERS_EN_CACHE);
					return reponseCache;
				}
			})()
		);
	}
});