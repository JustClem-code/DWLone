<template>
  <p>Test component</p>

  <div
    class="mx-auto block max-w-xl overflow-hidden shadow-xl bg-white/40 dark:bg-gray-800/50 outline outline-offset-2 rounded-lg shadow-xs dark:shadow-none outline-gray-300 dark:outline-gray-700/90 hover:outline-gray-500 transition-all">
    <div id="command" class="aizm aizo" style="--input-width: 576px;">
      <div class="grid grid-cols-[repeat(1,minmax(0,1fr))]">
        <input type="text" autofocus="" placeholder="Search..."
          class="col-start-1 row-start-1 h-[3rem] w-full bg-white/40 dark:bg-gray-800/50 pr-[1rem] pl-[2.75rem] outline-none text-base"
          id="command-input-0" role="combobox" aria-autocomplete="list" autocomplete="off"
          aria-controls="command-items-1" aria-activedescendant="item-39">
        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
          class="pointer-events-none col-start-1 row-start-1 ml-[1rem] size-5 self-center">
          <path
            d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
            clip-rule="evenodd" fill-rule="evenodd"></path>
        </svg>
      </div>

      <div class="flex max-h-80 scroll-py-[2.5rem] scroll-pb-[0.5rem] flex-col gap-1 overflow-y-auto p-1 pb-[0.5rem]"
        id="command-items-1" role="listbox">
        <div aria-labelledby="projects-label">
          <h2 id="projects-label" class="font-semibold text-sm">Projects</h2>
          <div class="-mx-4 mt-2 text-sm">
            <a href="#" data-type="project" class="flex cursor-default items-center px-1 py-2 select-none ajcd ajcu"
              :class="selected ? 'bg-purple' : ''" id="item-2" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Workflow Inc. / Website Redesign</span>
            </a>
            <a href="#" hidden="" data-type="project"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-3" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Workflow Inc. / Open Graph Image</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-4" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Workflow Inc. / Logo Design</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-5" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Workflow Inc. / Advertising Campaign</span>
            </a>
            <a href="#" hidden="" data-type="project"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-6" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Conglomerate Inc. / TV Ad Campaign</span>
            </a>
            <a href="#" hidden="" data-type="project"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-7" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Conglomerate Inc. / Mobile App</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-8" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Conglomerate Inc. / Product Design</span>
            </a>
            <a href="#" hidden="" data-type="project"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-9" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Conglomerate Inc. / Intranet</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-10" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Conglomerate Inc. / Custom Programming</span>
            </a>
            <a href="#" hidden="" data-type="project"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-11" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Multinational LLC. / Creative Writing</span>
            </a>
            <a href="#" hidden="" data-type="project"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-12" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Multinational LLC. / Animation</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-13" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Multinational LLC. / Product Illustration</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-14" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Products Inc. / Video Production</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-15" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Products Inc. / Print Brochure</span>
            </a>
            <a href="#" data-type="project" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-16" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none ajbw ajeh">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Products Inc. / Documentation</span>
            </a>
          </div>
        </div>

        <div aria-labelledby="users-label">
          <h2 id="users-label" class=" font-semibold text-xs">Users</h2>
          <div class="-mx-4 mt-2 text-sm ">
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-17" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Leslie Alexander</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-18" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Michael Foster</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-19" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Dries Vincent</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-20" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Lindsay Walton</span>
            </a>
            <a href="#" hidden="" data-type="user"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-21" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <img
                src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Courtney Henry</span>
            </a>
            <a href="#" hidden="" data-type="user"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-22" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <img
                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Tom Cook</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-23" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Whitney Francis</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-24" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Leonard Krasner</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-25" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Floyd Miles</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-26" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Emily Selman</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-27" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1500917293891-ef795e70e1f6?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Kristin Watson</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-28" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1505840717430-882ce147ef2d?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Emma Dorsey</span>
            </a>
            <a href="#" hidden="" data-type="user"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-29" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <img
                src="https://images.unsplash.com/photo-1509783236416-c9ad59bae472?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Alicia Bell</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-30" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1507101105822-7472b28e22ac?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Jenny Wilson</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-31" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Anna Roberts</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-32" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Benjamin Russel</span>
            </a>
            <a href="#" hidden="" data-type="user"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-33" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <img
                src="https://images.unsplash.com/photo-1517070208541-6ddc4d3efbcb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Jeffrey Webb</span>
            </a>
            <a href="#" hidden="" data-type="user"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-34" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <img
                src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Kathryn Murphy</span>
            </a>
            <a href="#" hidden="" data-type="user"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-35" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <img
                src="https://images.unsplash.com/photo-1513910367299-bce8d8a0ebf6?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Lawrence Hunter</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-36" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1506980595904-70325b7fdd90?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Yvette Armstrong</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-37" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1501031170107-cfd33f0cbdcc?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Angela Fisher</span>
            </a>
            <a href="#" hidden="" data-type="user"
              class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu " id="item-38" role="option"
              tabindex="-1" aria-selected="false" aria-hidden="true">
              <img
                src="https://images.unsplash.com/photo-1520785643438-5bf77931f493?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Blake Reid</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-39" role="option" tabindex="-1" aria-selected="true">
              <img
                src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Hector Gibbons</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-40" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Fabricio Mendes</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-41" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1504703395950-b89145a5425b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Jillian Steward</span>
            </a>
            <a href="#" data-type="user" class=" flex cursor-default items-center px-1 py-2 select-none ajcd ajcu "
              id="item-42" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none aizt ajae ajbn ajbp ajbr">
              <span class="ml-3 flex-auto text-ellipsis whitespace-nowrap overflow-hidden">Chelsea Hagon</span>
            </a>
          </div>
        </div>
      </div>

      <div class="hidden ajas px-14 text-center text-sm ajcg py-14">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
          aria-hidden="true" class="aiyh size-6">
          <path
            d="M16.712 4.33a9.027 9.027 0 0 1 1.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 0 0-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 0 1 0 9.424m-4.138-5.976a3.736 3.736 0 0 0-.88-1.388 3.737 3.737 0 0 0-1.388-.88m2.268 2.268a3.765 3.765 0 0 1 0 2.528m-2.268-4.796a3.765 3.765 0 0 0-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 0 1-1.388.88m2.268-2.268 4.138 3.448m0 0a9.027 9.027 0 0 1-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0-3.448-4.138m3.448 4.138a9.014 9.014 0 0 1-9.424 0m5.976-4.138a3.765 3.765 0 0 1-2.528 0m0 0a3.736 3.736 0 0 1-1.388-.88 3.737 3.737 0 0 1-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 0 1-1.652-1.306 9.027 9.027 0 0 1-1.306-1.652m0 0 4.138-3.448M4.33 16.712a9.014 9.014 0 0 1 0-9.424m4.138 5.976a3.765 3.765 0 0 1 0-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 0 1 1.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 0 0-1.652 1.306A9.025 9.025 0 0 0 4.33 7.288"
            stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <p class="aiyj font-semibold
 text-xs">Help with searching</p>
        <p class="mt-2 ajbg">Use this tool to quickly search for users and projects across our entire platform. You can
          also use the search modifiers found in the footer below to limit the results to just users or projects.</p>
      </div>

      <div hidden="" class="aiym ajas px-14 text-center text-sm ajch py-14">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
          aria-hidden="true" class="aiyh size-6">
          <path
            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
            stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <p class="aiyj font-semibold
 text-xs">No results found</p>
        <p class="mt-2 ajbg">We couldn’t find anything with that term. Please try again.</p>
      </div>

      <div class="flex aizh items-center ajaf px-1 ajav  ">Type <kbd class="aiyg flex size-5 items-center aizk aizv aizx aizz ajae font-semibold
 text-xs ajcm ajco ajdd">#</kbd> <span class="ajde">for
          projects,</span><span class="hidden ajdf">to access projects,</span><kbd class="aiyg flex size-5 items-center aizk aizv aizx aizz ajae font-semibold
 text-xs ajcq ajcs ajdd">&gt;</kbd> for users, and <kbd class="aiyg flex size-5 items-center aizk aizv aizx aizz ajae font-semibold
 text-xs ajci ajck ajdd">?</kbd> for help.</div>
    </div>
  </div>
</template>
