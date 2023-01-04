<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />

  <main id="app" class="relative flex flex-col min-h-screen bg-purple-100 font-roboto">
    <div class="flex flex-col flex-1">
      <header
        style="clip-path: polygon(0 0, 100% 0, 100% 98%, 0 92%)"
        class="relative overflow-hidden pattern bg-slate-900"
      >
        <div class="relative pt-6 pb-16 sm:pb-32">
          <navbar colors="text-white hover:text-blue-400"> </navbar>

          <main class="mt-16 sm:mt-20">
            <div class="container px-4 mx-auto sm:px-6">
              <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <div
                  class="sm:text-center lg:col-span-6 lg:text-left lg:flex lg:items-center"
                >
                  <div>
                    <p
                      class="inline-flex items-center p-1 pr-2 text-white bg-purple-800 rounded-full sm:text-base lg:text-sm xl:text-base hover:text-purple-200"
                    >
                      <span class="mx-4 text-sm">Metaworks</span>
                      <span
                        class="px-3 py-0.5 text-white text-xs font-semibold leading-5 uppercase tracking-wide bg-blue-600 rounded-full"
                        >Coming Soon</span
                      >
                    </p>

                    <h1
                      class="mt-4 text-3xl font-bold text-white sm:mt-5 sm:leading-none lg:mt-6 lg:text-4xl 2xl:text-5xl"
                    >
                      <span class="md:block">Custom UI Components Built</span>
                      <span class="text-blue-400 md:block">
                        with Tailwind CSS & Alpine JS</span
                      >
                    </h1>

                    <p
                      class="mt-3 text-base text-purple-300 sm:mt-5 sm:text-xl xl:text-lg"
                    >
                      Meraki UI is Tailwind CSS Components That Support RTL Languages &
                      Fully Responsive Based On Flexbox & CSS Grid with elegant Dark Mode.
                    </p>

                    <div
                      class="flex flex-wrap items-center justify-center mt-8 space-x-5 lg:justify-start"
                    >
                      <a
                        aria-label="components"
                        href="/components"
                        class="flex text-white hover:underline"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-6 h-6"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"
                          />
                        </svg>

                        <p class="mx-2">144 teleworkers</p>
                      </a>

                      <a
                        aria-label="MIT License"
                        class="flex text-white hover:underline"
                        target="_blank"
                        href="https://github.com/merakiui/merakiui/blob/master/LICENSE"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                          class="w-6 h-6"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z"
                          />
                        </svg>

                        <p class="mx-2">MIT Licensed</p>
                      </a>
                    </div>

                    <div
                      v-if="$page.props.auth.user"
                      class="grid grid-cols-1 gap-4 mt-8 text-center lg:justify-start sm:justify-center sm:flex sm:gap-0 sm:space-x-4"
                    >
                      <Link
                        :href="route('Tasks')"
                        class="px-6 py-3 text-sm font-medium text-purple-900 capitalize transition-colors duration-300 bg-white rounded-lg hover:bg-purple-300 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-purple-100"
                        >Tableau de bord</Link
                      >
                    </div>
                    <div
                      v-else
                      class="grid grid-cols-1 gap-4 mt-8 text-center lg:justify-start sm:justify-center sm:flex sm:gap-0 sm:space-x-4"
                    >
                      <Link
                        :href="route('login')"
                        class="px-6 py-3 text-sm font-medium text-purple-900 capitalize transition-colors duration-300 bg-white rounded-lg hover:bg-purple-300 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-purple-100"
                      >
                        CONNECTER</Link
                      >
                      <Link
                        aria-label="Premium Themes"
                        :href="route('register')"
                        class="flex items-center justify-center px-6 py-3 space-x-2 text-sm font-medium text-white transition-colors duration-300 bg-purple-800 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-purple-600"
                      >
                        <span>S'INSCRIRE </span>

                        <svg
                          aria-hidden="true"
                          xmlns="http://www.w3.org/2000/svg"
                          class="w-5 h-5"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"
                          />
                        </svg>
                      </Link>
                    </div>
                  </div>
                </div>

                <div
                  class="flex items-center justify-end mt-12 sm:mt-16 lg:mt-0 lg:col-span-6"
                >
                  <img class="xl:max-w-xl rounded" src="landingImage.jpg" />
                </div>
              </div>
            </div>
          </main>
        </div>
      </header>

      <section class="container px-4 py-12 mx-auto sm:py-20 sm:px-6">
        <div class="lg:flex lg:items-center lg:justify-between lg:space-x-12">
          <div>
            <h1 class="text-2xl font-bold text-purple-800 sm:text-3xl">
              Tailwind CSS Components
            </h1>
            <p class="max-w-2xl mt-4 text-base text-purple-600">
              Alert, Heroes, features, sign up forms, CTA, and everything you need to
              build your websites fast with RTL Languages Support.
            </p>
          </div>

          <div class="flex items-center justify-center mt-8 lg:mt-0">
            <carbon-ads></carbon-ads>
          </div>
        </div>

        <div
          class="grid grid-cols-1 gap-8 mt-12 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4"
        >
          <a
            href="/components/cards"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Cards" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">Cards</h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">9 components</p>
            </div>
          </a>
          <a
            href="/components/features"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Features" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">
                Features
              </h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">7 components</p>
            </div>
          </a>
          <a
            href="/components/tabs"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Tabs" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">Tabs</h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">4 components</p>
            </div>
          </a>
          <a
            href="/components/testimonials"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Testimonials" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">
                Testimonials
              </h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">8 components</p>
            </div>
          </a>
          <a
            href="/components/footers"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Footers" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">Footers</h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">8 components</p>
            </div>
          </a>
          <a
            href="/components/sign-in-and-registration"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Sign in and Registration" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">
                Sign in and Registration
              </h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">9 components</p>
            </div>
          </a>
          <a
            href="/components/blog"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Blog" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">Blog</h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">6 components</p>
            </div>
          </a>
          <a
            href="/components/dropdowns"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img class="rounded-lg" src="appLogo.jpg" alt="Dropdowns" />

            <div class="mt-4">
              <h2 class="text-xl font-semibold tracking-wide text-purple-800">
                Dropdowns
              </h2>
              <p class="mt-1 text-sm text-purple-500 sm:text-base">6 components</p>
            </div>
          </a>
        </div>

        <div class="flex justify-center mt-12">
          <a
            href="/components"
            class="relative flex items-center h-10 px-6 text-sm font-medium text-white transition-colors duration-300 bg-purple-800 rounded-lg pointer-events-auto hover:bg-purple-600 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-purple-700"
            >Show All Components</a
          >
        </div>
      </section>

      <section class="container px-4 py-12 mx-auto sm:py-20 sm:px-6">
        <div class="lg:flex lg:items-center lg:justify-between lg:space-x-12">
          <div>
            <h1 class="text-2xl font-bold text-purple-800 sm:text-3xl">
              Premium Tailwind CSS Themes
            </h1>
            <p class="max-w-2xl mt-4 text-base text-purple-600">
              Tailwind CSS landing pages, dashboard, and themes that shapes with RTL
              Languages Support. built with AlpineJs, Laravel Blade, and Vue3 Templates.
            </p>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-8 mt-8 md:grid-cols-2 lg:mt-12 xl:grid-cols-3">
          <a
            href="/themes/1"
            target="_blank"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img
              class="object-cover rounded-lg"
              src="appLogo.jpg"
              alt="Courses Dashboard"
            />

            <div class="mt-6">
              <div class="flex items-center justify-between space-x-4">
                <h2 class="text-xl font-semibold tracking-wide text-purple-800">
                  Courses Dashboard
                </h2>

                <p class="font-medium tracking-wider text-blue-600 uppercase">Free</p>
              </div>
              <p class="mt-2 text-sm text-purple-500 sm:text-base">
                Courses Dashboard Template Build with Tailwind CSS, vite, vue 3, and
                chart.js shapes with RTL Languages Support.
              </p>
            </div>
          </a>
          <a
            href="/themes/3"
            target="_blank"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img
              class="object-cover rounded-lg"
              src="appLogo.jpg"
              alt="Botanical Garden"
            />

            <div class="mt-6">
              <div class="flex items-center justify-between space-x-4">
                <h2 class="text-xl font-semibold tracking-wide text-purple-800">
                  Botanical Garden
                </h2>

                <p class="font-medium tracking-wider text-blue-600 uppercase">Free</p>
              </div>
              <p class="mt-2 text-sm text-purple-500 sm:text-base">
                Botanical Garden Template Build with Tailwind CSS, vite, and vue 3 shapes
                with RTL Languages Support.
              </p>
            </div>
          </a>
          <a
            href="/themes/4"
            target="_blank"
            class="p-6 overflow-hidden duration-300 transform bg-white border-2 border-transparent rounded-lg shadow-sm hover:border-blue-400 hover:scale-105"
          >
            <img
              class="object-cover rounded-lg"
              src="appLogo.jpg"
              alt="Meraki UI Dashboard"
            />

            <div class="mt-6">
              <div class="flex items-center justify-between space-x-4">
                <h2 class="text-xl font-semibold tracking-wide text-purple-800">
                  Metaworks
                </h2>

                <div class="flex items-center space-x-2">
                  <p class="font-medium text-blue-600">telework</p>
                </div>
              </div>
              <p class="mt-2 text-sm text-purple-500 sm:text-base">
                Tailwind CSS Dashboard Comes with RTL Support, Built Laravel blade, HTML
                and Alpine JS.
              </p>
            </div>
          </a>
        </div>

        <div class="flex justify-center mt-12">
          <a
            href="/themes"
            class="relative flex items-center h-10 px-6 text-sm font-medium text-white transition-colors duration-300 bg-purple-800 rounded-lg pointer-events-auto hover:bg-purple-600 focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-purple-700"
            >Show All Themes</a
          >
        </div>
      </section>

      <section class="container px-4 pb-20 mx-auto sm:px-6">
        <h2 class="text-2xl font-bold text-purple-800 md:text-3xl">
          Don't take Our words for it
        </h2>
        <p class="mt-4 text-lg text-purple-600">
          Trust The Developers from Tailwind CSS Community
        </p>

        <testimonials class="mt-12"></testimonials>
      </section>
    </div>

    <section class="bg-white">
      <div class="container px-4 pt-20 pb-10 mx-auto sm:px-6 lg:flex lg:items-center">
        <div class="lg:w-0 lg:flex-1">
          <h2 class="text-2xl font-bold text-purple-800 md:text-3xl">
            Sign up for our newsletter
          </h2>
          <p class="max-w-3xl mt-4 text-lg text-purple-500">
            Subscribe to our newsletter to notify when we publish new components
          </p>
        </div>

        <newsletter></newsletter>
      </div>
    </section>

    <footer class="bg-white">
      <div
        class="container px-4 py-8 mx-auto sm:px-6 md:flex md:items-center md:justify-between"
      >
        <div class="flex justify-center space-x-6 md:order-2">
          <a
            href="https://twitter.com/meraki_ui"
            target="_blank"
            class="text-purple-500 transition-colors duration-300 hover:text-blue-500"
          >
            <span class="sr-only">Twitter</span>
            <svg
              class="w-6 h-6"
              fill="currentColor"
              viewBox="0 0 24 24"
              aria-hidden="true"
            >
              <path
                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"
              />
            </svg>
          </a>

          <a
            href="https://github.com/merakiui/merakiui"
            target="_blank"
            class="text-purple-500 transition-colors duration-300 hover:text-blue-500"
          >
            <span class="sr-only">GitHub</span>
            <svg
              class="w-6 h-6"
              fill="currentColor"
              viewBox="0 0 24 24"
              aria-hidden="true"
            >
              <path
                fill-rule="evenodd"
                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                clip-rule="evenodd"
              />
            </svg>
          </a>
        </div>

        <div class="mt-8 md:mt-0 md:order-1">
          <p class="text-base text-center text-purple-500">
            &copy; 2022 Meraki UI. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </main>
</template>
