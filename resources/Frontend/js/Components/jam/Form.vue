<template>
  <div class="px-6 antialiased text-gray-900">
    <div class="max-w-xl py-12 mx-auto divide-y md:max-w-4xl">
      <div class="py-12">
        <h2 class="text-2xl font-bold">Unstyled</h2>
        <p class="mt-2 text-lg text-gray-600">
          This is how form elements look out of the box.
        </p>
        <div class="max-w-md mt-8">
          <div class="grid grid-cols-1 gap-6">
            <label class="block">
              <span class="text-gray-700">Full name</span>
              <input type="text" class="block w-full mt-1" placeholder="" />
            </label>
            <label class="block">
              <span class="text-gray-700">Email address</span>
              <input
                type="email"
                class="block w-full mt-1"
                placeholder="john@example.com"
              />
            </label>
            <label class="block">
              <span class="text-gray-700">When is your event?</span>
              <input type="date" class="block w-full mt-1" />
            </label>
            <label class="block">
              <span class="text-gray-700">What type of event is it?</span>
              <select class="block w-full mt-1">
                <option>Corporate event</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Other</option>
              </select>
            </label>
            <label class="block">
              <span class="text-gray-700">Additional details</span>
              <textarea class="block w-full mt-1" rows="3"></textarea>
            </label>
            <div class="block">
              <div class="mt-2">
                <div>
                  <label class="inline-flex items-center">
                    <input type="checkbox" checked />
                    <span class="ml-2">Email me news and special offers</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="py-12">
        <h2 class="text-2xl font-bold">Simple</h2>
        <div class="max-w-md mt-8">
          <div class="grid grid-cols-1 gap-6">
            <label class="block">
              <span class="text-gray-700">Full name</span>
              <input
                type="text"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder=""
              />
            </label>
            <label class="block">
              <span class="text-gray-700">Email address</span>
              <input
                type="email"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                placeholder="john@example.com"
              />
            </label>
            <label class="block">
              <span class="text-gray-700">When is your event?</span>
              <input
                type="date"
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              />
            </label>
            <label class="block">
              <span class="text-gray-700">What type of event is it?</span>
              <select
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              >
                <option>Corporate event</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Other</option>
              </select>
            </label>
            <label class="block">
              <span class="text-gray-700">Additional details</span>
              <textarea
                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                rows="3"
              ></textarea>
            </label>
            <div class="block">
              <div class="mt-2">
                <div>
                  <label class="inline-flex items-center">
                    <input
                      type="checkbox"
                      class="text-indigo-600 border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50"
                      checked
                    />
                    <span class="ml-2">Email me news and special offers</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="py-12">
        <h2 class="text-2xl font-bold">Underline</h2>
        <div class="max-w-md mt-8">
          <div class="grid grid-cols-1 gap-6">
            <label class="block">
              <span class="text-gray-700">Full name</span>
              <input
                type="text"
                class="mt-0 block w-full px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-black"
                placeholder=""
              />
            </label>
            <label class="block">
              <span class="text-gray-700">Email address</span>
              <input
                type="email"
                class="mt-0 block w-full px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-black"
                placeholder="john@example.com"
              />
            </label>
            <label class="block">
              <span class="text-gray-700">When is your event?</span>
              <input
                type="date"
                class="mt-0 block w-full px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-black"
              />
            </label>
            <label class="block">
              <span class="text-gray-700">What type of event is it?</span>
              <select
                class="block w-full mt-0 px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-black"
              >
                <option>Corporate event</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Other</option>
              </select>
            </label>
            <label class="block">
              <span class="text-gray-700">Additional details</span>
              <textarea
                class="mt-0 block w-full px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-black"
                rows="2"
              ></textarea>
            </label>
            <div class="block">
              <div class="mt-2">
                <div>
                  <label class="inline-flex items-center">
                    <input
                      type="checkbox"
                      class="text-black border-2 border-gray-300 focus:border-gray-300 focus:ring-black"
                    />
                    <span class="ml-2">Email me news and special offers</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="py-12">
        <h2 class="text-2xl font-bold">Solid</h2>
        <div class="max-w-md mt-8">
          <div class="grid grid-cols-1 gap-6">
            <label class="block">
              <span class="text-gray-700">Full name</span>
              <input
                type="text"
                class="block w-full mt-1 bg-gray-100 border-transparent rounded-md focus:border-gray-500 focus:bg-white focus:ring-0"
                placeholder=""
              />
            </label>
            <label class="block">
              <span class="text-gray-700">Email address</span>
              <input
                type="email"
                class="block w-full mt-1 bg-gray-100 border-transparent rounded-md focus:border-gray-500 focus:bg-white focus:ring-0"
                placeholder="john@example.com"
              />
            </label>
            <label class="block">
              <span class="text-gray-700">When is your event?</span>
              <input
                type="date"
                class="block w-full mt-1 bg-gray-100 border-transparent rounded-md focus:border-gray-500 focus:bg-white focus:ring-0"
              />
            </label>
            <label class="block">
              <span class="text-gray-700">What type of event is it?</span>
              <select
                class="block w-full mt-1 bg-gray-100 border-transparent rounded-md focus:border-gray-500 focus:bg-white focus:ring-0"
              >
                <option>Corporate event</option>
                <option>Wedding</option>
                <option>Birthday</option>
                <option>Other</option>
              </select>
            </label>
            <label class="block">
              <span class="text-gray-700">Additional details</span>
              <textarea
                class="block w-full mt-1 bg-gray-100 border-transparent rounded-md focus:border-gray-500 focus:bg-white focus:ring-0"
                rows="3"
              ></textarea>
            </label>
            <div class="block">
              <div class="mt-2">
                <div>
                  <label class="inline-flex items-center">
                    <input
                      type="checkbox"
                      class="text-gray-700 bg-gray-200 border-transparent rounded focus:border-transparent focus:bg-gray-200 focus:ring-1 focus:ring-offset-2 focus:ring-gray-500"
                    />
                    <span class="ml-2">Email me news and special offers</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
