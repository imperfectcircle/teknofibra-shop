<header
    x-data="{
      mobileMenuOpen: false,
      cartItemsCount: {{ \App\Http\Helpers\CartHelper::getCartItemsCount() }},
    }"
    @cart-change.window="cartItemsCount = $event.detail.count"
    id="header"
    class="fixed top-0 left-0 z-50 w-full flex justify-between items-center bg-slate-800 shadow-md text-white"
    >
      <div class="flex items-center space-x-5">
        <a href="{{ route('home') }}" class="block py-navbar-item pl-5"> 
          <img class="w-[250px]" src="/img/logo.png" alt="Teknofibra Logo">
        </a>
        <a href="{{ route('home') }}" class="hidden md:block py-navbar-item pl-5"> 
          Home
        </a>
        <x-locale lang='it' nation='it' />
        <x-locale lang='en' nation='gb' />
      </div>
      <!-- Responsive Menu -->
      <div
        class="block fixed z-10 top-0 bottom-0 pt-10 height h-full w-[220px] transition-all bg-slate-900 md:hidden"
        :class="mobileMenuOpen ? 'left-0' : '-left-[220px]'"
      >
        <ul>
          <a href="{{ route('home') }}" class="block py-navbar-item pl-5"> 
            Home
          </a>
        @if (!Auth::guest())
          <li x-data="{open: false}" class="relative">
            <a
              @click="open = !open"
              class="cursor-pointer flex justify-between items-center py-2 px-3 hover:bg-slate-800"
            >
              <span class="flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 mr-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                {{ __('ui.myaccount') }}
              </span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </a>
            <ul
              x-show="open"
              x-transition
              class="z-10 right-0 bg-slate-800 py-2"
            >
              <li>
                <a
                  href="{{ route('profile') }}"
                  class="flex px-3 py-2 hover:bg-slate-900"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                  {{ __('ui.myprofile') }}
                </a>
              </li>
              
              <li class="hover:bg-slate-900">
                <a
                  href="{{ route('orders.index') }}"
                  class="flex items-center px-3 py-2 hover:bg-slate-900"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    />
                  </svg>
                  {{ __('ui.myorders') }}
                </a>
              </li>
              <li class="hover:bg-slate-900">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center px-3 py-2 hover:bg-slate-900">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                        {{ __('ui.logout') }}
                    </button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li>
            <a
              href="{{ route('login') }}"
              class="flex items-center py-2 px-3 transition-colors hover:bg-slate-800"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                />
              </svg>
              {{ __('ui.login') }}
            </a>
          </li>
          <li class="px-3 py-3">
            <a
              href="{{ route('register') }}"
              class="block text-center text-white bg-emerald-600 py-2 px-3 rounded shadow-md hover:bg-emerald-700 active:bg-emerald-800 transition-colors w-full"
            >
              {{ __('ui.signup') }}
            </a>
          </li>
        @endif
        </ul>
      </div>
      <!--/ Responsive Menu -->

      <!-- Search & Sort -->
      @if (request()->is('/') || request()->is('category/*'))
        <div class="hidden md:flex md:gap-2 md:items-center p-3 pb-0 my-5" x-data="{
          selectedSort: '{{ request()->get('sort', '-updated_at') }}',
          searchKeyword: '{{ request()->get('search') }}',
          updateUrl() {
              const params = new URLSearchParams(window.location.search)
              if (this.selectedSort && this.selectedSort !== '-updated_at') {
                  params.set('sort', this.selectedSort)
              } else {
                  params.delete('sort')
              }

              if (this.searchKeyword) {
                  params.set('search', this.searchKeyword)
              } else {
                  params.delete('search')
              }
              window.location.href = window.location.origin + window.location.pathname + '?'
              + params.toString();
          }
        }">
            <form action="" method="GET" class="flex-1" @submit.prevent="updateUrl">
                <x-text-input class="text-black" type="text" name="search" placeholder="{{ __('ui.search') }}"
                        x-model="searchKeyword"/>
            </form>
            <x-text-input
                x-model="selectedSort"
                @change="updateUrl"
                type="select"
                name="sort"
                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded text-black">
                <option value="price">{{ __('ui.price') }}</option>
                <option value="-price">{{ __('ui.-price') }}</option>
                <option value="title">{{ __('ui.name') }}</option>
                <option value="-title">{{ __('ui.-name') }}</option>
                <option value="-updated_at">{{ __('ui.date') }}</option>
                <option value="updated_at">{{ __('ui.-date') }}</option>
            </x-text-input>
        </div>
      @endif
      
      <!--/ Search & Sort -->
      
      <nav id="navbar" class="hidden md:block">
        <ul class="grid grid-flow-col items-center">
        @if (!Auth::guest())
          <li x-data="{open: false}" class="relative">
            <a
              @click="open = !open"
              class="cursor-pointer flex items-center py-navbar-item px-navbar-item pr-5 hover:bg-slate-900"
            >
              <span class="flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 mr-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                {{ __('ui.myaccount') }}
              </span>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 ml-2"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </a>
            <ul
              @click.outside="open = false"
              x-show="open"
              x-transition
              x-cloak
              class="absolute z-10 right-0 bg-slate-800 py-2 w-48"
            >
              <li>
                <a
                  href="{{ route('profile') }}"
                  class="flex px-3 py-2 hover:bg-slate-900"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                  {{ __('ui.myprofile') }}
                </a>
              </li>
              
              <li>
                <a
                  href="{{ route('orders.index') }}"
                  class="flex px-3 py-2 hover:bg-slate-900"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    />
                  </svg>
                  {{ __('ui.myorders') }}
                </a>
              </li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full px-3 py-2 hover:bg-slate-900">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                        {{ __('ui.logout') }}
                    </button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li>
            <a
              href="{{ route('login') }}"
              class="flex items-center py-navbar-item px-navbar-item hover:bg-slate-900"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                />
              </svg>
              {{ __('ui.login') }}
            </a>
          </li>

          <li>
            <a
              href="{{ route('register') }}"
              class="inline-flex items-center text-white bg-emerald-600 py-2 px-3 rounded shadow-md hover:bg-emerald-700 active:bg-emerald-800 transition-colors mx-5"
            >
              {{ __('ui.signup') }}
            </a>
          </li>
        @endif
        </ul>
      </nav>
      <button
        @click="mobileMenuOpen = !mobileMenuOpen"
        class="p-4 block md:hidden"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M4 6h16M4 12h16M4 18h16"
          />
        </svg>
      </button>
      
    </header>
