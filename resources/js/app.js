import "./bootstrap";

import { openobserveRum } from "@openobserve/browser-rum";
import { openobserveLogs } from "@openobserve/browser-logs";

import { createInertiaApp } from "@inertiajs/svelte";
import { mount } from "svelte";

/*
|--------------------------------------------------------------------------
| OpenObserve RUM + Logs
|--------------------------------------------------------------------------
*/

const options = {
    clientToken: "rumeQS9oetkggs3hwQB",
    applicationId: "laravel-inertia-app",
    site: "10.255.20.253:5080",
    service: "laravel-observe",
    env: "development",
    version: "1.0.0",
    organizationIdentifier: "default",
    insecureHTTP: true,
    apiVersion: "v1",
};

openobserveRum.init({
    applicationId: options.applicationId,
    clientToken: options.clientToken,
    site: options.site,
    organizationIdentifier: options.organizationIdentifier,
    service: options.service,
    env: options.env,
    version: options.version,

    trackResources: true,
    trackLongTasks: true,
    trackUserInteractions: true,

    sessionSampleRate: 100,
    sessionReplaySampleRate: 50,

    defaultPrivacyLevel: "mask-user-input",

    insecureHTTP: options.insecureHTTP,
    apiVersion: options.apiVersion,
});

openobserveLogs.init({
    clientToken: options.clientToken,
    site: options.site,
    organizationIdentifier: options.organizationIdentifier,
    service: options.service,
    env: options.env,
    version: options.version,

    forwardErrorsToLogs: true,

    insecureHTTP: options.insecureHTTP,
    apiVersion: options.apiVersion,
});

openobserveRum.startSessionReplayRecording();

/*
|--------------------------------------------------------------------------
| Inertia App
|--------------------------------------------------------------------------
*/

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.svelte", {
            eager: true,
        });

        return pages[`./Pages/${name}.svelte`];
    },

    setup({ el, App, props }) {
        mount(App, {
            target: el,
            props,
        });
    },
});