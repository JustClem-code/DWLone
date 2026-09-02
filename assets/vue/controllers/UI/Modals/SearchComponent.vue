<template>

  <div class="mx-auto block max-w-2xl overflow-hidden shadow-xl bg-white/40 dark:bg-gray-800/50
    outline outline-offset-2 outline-gray-300 dark:outline-gray-700/90 hover:outline-gray-500
    rounded-lg shadow-xs dark:shadow-none transition-all">

    <div id="command">

      <div class="grid grid-cols-[repeat(1,minmax(0,1fr))]">

        <input type="text" autofocus="true" placeholder="Search..."
          class="col-start-1 row-start-1 h-12 w-full pr-4 pl-11 outline-none text-base bg-white/40 dark:bg-gray-800/50"
          id="command-input-0" role="combobox" aria-autocomplete="list" autocomplete="off"
          aria-controls="command-items-1" aria-activedescendant="item-39">

        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
          class="col-start-1 row-start-1 ml-4 size-5 self-center pointer-events-none text-gray-200 dark:text-gray-500">
          <path
            d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
            clip-rule="evenodd" fill-rule="evenodd"></path>
        </svg>
      </div>

      <div class="flex flex-col gap-1 max-h-80 scroll-py-10 scroll-pb-2 overflow-y-auto pt-1 pb-2"
        id="command-items-1" role="listbox">

        <div aria-labelledby="projects-label">

          <h2 id="projects-label" class="px-2 font-semibold text-sm">Projects</h2>

          <div class="px-2 mt-2 text-sm hover:bg-purple-600">

            <a href="#" data-type="project" class="flex items-center cursor-default px-1 py-2 select-none"
              id="item-2" role="option" tabindex="-1" aria-selected="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="flex-auto ml-3 text-ellipsis whitespace-nowrap overflow-hidden">Workflow Inc. / Website
                Redesign</span>
            </a>

          </div>

        </div>

        <div aria-labelledby="users-label">

          <h2 id="users-label" class="px-2 font-semibold text-xs">Users</h2>

          <div class="px-2 mt-2 text-sm ">

            <a href="#" data-type="user" class="flex items-center cursor-default px-1 py-2 select-none "
              id="item-17" role="option" tabindex="-1" aria-selected="false">
              <img
                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                alt="" class="size-6 flex-none">
              <span class="flex-auto ml-3 text-ellipsis whitespace-nowrap overflow-hidden">Leslie Alexander</span>
            </a>

          </div>

        </div>

      </div>

    </div>
  </div>
</template>
