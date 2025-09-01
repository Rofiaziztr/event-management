<div
    class="w-80 sm:w-159.5 flex flex-col bg-white border border-gray-200 shadow-lg rounded-xl overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
    <!-- Calendar -->
    <div class="sm:flex">
        <!-- Calendar -->
        <div class="p-3 space-y-0.5">
            <!-- Months -->
            <div class="grid grid-cols-5 items-center gap-x-3 mx-1.5 pb-3">
                <!-- Prev Button -->
                <div class="col-span-1">
                    <button type="button"
                        class="size-8 flex justify-center items-center text-gray-800 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        aria-label="Previous">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                    </button>
                </div>
                <!-- End Prev Button -->

                <!-- Month / Year -->
                <div class="col-span-3 flex justify-center items-center gap-x-1">
                    <div class="relative">
                        <select
                            data-hs-select='{
                "placeholder": "Select month",
                "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative flex text-nowrap w-full cursor-pointer text-start font-medium text-gray-800 hover:text-blue-600 focus:outline-hidden focus:text-blue-600 before:absolute before:inset-0 before:z-1 dark:text-neutral-200 dark:hover:text-blue-500 dark:focus:text-blue-500",
                "dropdownClasses": "mt-2 z-50 w-32 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                "optionClasses": "p-2 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-gray-800 dark:text-neutral-200\" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>"
              }'
                            class="hidden">
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6" selected>July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                        </select>
                    </div>

                    <span class="text-gray-800 dark:text-neutral-200">/</span>

                    <div class="relative">
                        <select
                            data-hs-select='{
                "placeholder": "Select year",
                "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative flex text-nowrap w-full cursor-pointer text-start font-medium text-gray-800 hover:text-blue-600 focus:outline-hidden focus:text-blue-600 before:absolute before:inset-0 before:z-1 dark:text-neutral-200 dark:hover:text-blue-500 dark:focus:text-blue-500",
                "dropdownClasses": "mt-2 z-50 w-20 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                "optionClasses": "p-2 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-gray-800 dark:text-neutral-200\" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>"
              }'
                            class="hidden">
                            <option selected>2023</option>
                            <option>2024</option>
                            <option>2025</option>
                            <option>2026</option>
                            <option>2027</option>
                        </select>
                    </div>
                </div>
                <!-- End Month / Year -->

                <!-- Next Button -->
                <div class="col-span-1 flex justify-end">
                    <button type="button"
                        class="opacity-0 pointer-events-none size-8 flex justify-center items-center text-gray-800 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        aria-label="Next">
                        <svg class="shrink-0 size-4" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
                <!-- End Next Button -->
            </div>
            <!-- Months -->

            <!-- Weeks -->
            <div class="flex pb-1.5">
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Mo
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Tu
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    We
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Th
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Fr
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Sa
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Su
                </span>
            </div>
            <!-- Weeks -->

            <!-- Days -->
            <div class="flex">
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500"
                        disabled>
                        26
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500"
                        disabled>
                        27
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500"
                        disabled>
                        28
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500"
                        disabled>
                        29
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500"
                        disabled>
                        30
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        1
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        2
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        3
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        4
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        5
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        6
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        7
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        8
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        9
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        10
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        11
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        12
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        13
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        14
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        15
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        16
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        17
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        18
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        19
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        20
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        21
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        22
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        23
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        24
                    </button>
                </div>
                <div class="bg-gray-100 rounded-s-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center bg-blue-600 border-[1.5px] border-transparent text-sm font-medium text-white hover:border-blue-600 rounded-full dark:bg-blue-500 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:hover:border-neutral-700">
                        25
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        26
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        27
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        28
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        29
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        30
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        31
                    </button>
                </div>
                <div class="bg-linear-to-r from-gray-100 dark:from-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        1
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        2
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        3
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        4
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        5
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        6
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Time -->
            <div class="pt-3 flex justify-center items-center gap-x-2">
                <!-- Select -->
                <div class="relative">
                    <select
                        data-hs-select='{
              "placeholder": "Select option...",
              "viewport": "#with-time-tab-preview-datepicker",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-1 px-2 pe-6 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
              "dropdownClasses": "mt-2 z-50 w-full min-w-24 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto dark:bg-neutral-900 dark:border-neutral-700",
              "dropdownVerticalFixedPlacement": "top",
              "optionClasses": "hs-selected:bg-gray-100 dark:hs-selected:bg-neutral-800 py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:hs-selected:bg-gray-700 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span></div>"
            }'
                        class="hidden">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option selected>12</option>
                    </select>

                    <div class="absolute top-1/2 end-2 -translate-y-1/2">
                        <svg class="shrink-0 size-3 text-gray-500 dark:text-neutral-500"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg>
                    </div>
                </div>
                <!-- End Select -->

                <span class="text-gray-800 dark:text-white">:</span>

                <!-- Select -->
                <div class="relative">
                    <select
                        data-hs-select='{
              "placeholder": "Select option...",
              "viewport": "#with-time-tab-preview-datepicker",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-1 px-2 pe-6 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
              "dropdownClasses": "mt-2 z-50 w-full min-w-24 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto dark:bg-neutral-900 dark:border-neutral-700",
              "dropdownVerticalFixedPlacement": "top",
              "optionClasses": "hs-selected:bg-gray-100 dark:hs-selected:bg-neutral-800 py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:hs-selected:bg-gray-700 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span></div>"
            }'
                        class="hidden">
                        <option selected>00</option>
                        <option>01</option>
                        <option>02</option>
                        <option>03</option>
                        <option>04</option>
                        <option>05</option>
                        <option>06</option>
                        <option>07</option>
                        <option>08</option>
                        <option>09</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                        <option>21</option>
                        <option>22</option>
                        <option>23</option>
                        <option>24</option>
                        <option>25</option>
                        <option>26</option>
                        <option>27</option>
                        <option>28</option>
                        <option>29</option>
                        <option>30</option>
                        <option>31</option>
                        <option>32</option>
                        <option>33</option>
                        <option>34</option>
                        <option>35</option>
                        <option>36</option>
                        <option>37</option>
                        <option>38</option>
                        <option>39</option>
                        <option>40</option>
                        <option>41</option>
                        <option>42</option>
                        <option>43</option>
                        <option>44</option>
                        <option>45</option>
                        <option>46</option>
                        <option>47</option>
                        <option>48</option>
                        <option>49</option>
                        <option>50</option>
                        <option>51</option>
                        <option>52</option>
                        <option>53</option>
                        <option>54</option>
                        <option>55</option>
                        <option>56</option>
                        <option>57</option>
                        <option>58</option>
                        <option>59</option>
                    </select>

                    <div class="absolute top-1/2 end-2 -translate-y-1/2">
                        <svg class="shrink-0 size-3 text-gray-500 dark:text-neutral-500"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg>
                    </div>
                </div>
                <!-- End Select -->

                <!-- Select -->
                <div class="relative">
                    <select
                        data-hs-select='{
              "placeholder": "Select option...",
              "viewport": "#with-time-tab-preview-datepicker",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-1 px-2 pe-6 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
              "dropdownClasses": "mt-2 z-50 w-full min-w-24 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto dark:bg-neutral-900 dark:border-neutral-700",
              "dropdownVerticalFixedPlacement": "top",
              "optionClasses": "hs-selected:bg-gray-100 dark:hs-selected:bg-neutral-800 py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:hs-selected:bg-gray-700 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span></div>"
            }'
                        class="hidden">
                        <option selected>PM</option>
                        <option>AM</option>
                    </select>

                    <div class="absolute top-1/2 end-2 -translate-y-1/2">
                        <svg class="shrink-0 size-3 text-gray-500 dark:text-neutral-500"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg>
                    </div>
                </div>
                <!-- End Select -->
            </div>
            <!-- End Time -->
        </div>

        <!-- Calendar -->
        <div class="p-3 space-y-0.5">
            <!-- Months -->
            <div class="grid grid-cols-5 items-center gap-x-3 mx-1.5 pb-3">
                <!-- Prev Button -->
                <div class="col-span-1">
                    <button type="button"
                        class="opacity-0 pointer-events-none size-8 flex justify-center items-center text-gray-800 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        aria-label="Previous">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                    </button>
                </div>
                <!-- End Prev Button -->

                <!-- Month / Year -->
                <div class="col-span-3 flex justify-center items-center gap-x-1">
                    <div class="relative">
                        <select
                            data-hs-select='{
                "placeholder": "Select month",
                "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative flex text-nowrap w-full cursor-pointer text-start font-medium text-gray-800 hover:text-blue-600 focus:outline-hidden focus:text-blue-600 before:absolute before:inset-0 before:z-1 dark:text-neutral-200 dark:hover:text-blue-500 dark:focus:text-blue-500",
                "dropdownClasses": "mt-2 z-50 w-32 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                "optionClasses": "p-2 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-gray-800 dark:text-neutral-200\" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>"
              }'
                            class="hidden">
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6" selected>July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                        </select>
                    </div>

                    <span class="text-gray-800 dark:text-neutral-200">/</span>

                    <div class="relative">
                        <select
                            data-hs-select='{
                "placeholder": "Select year",
                "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative flex text-nowrap w-full cursor-pointer text-start font-medium text-gray-800 hover:text-blue-600 focus:outline-hidden focus:text-blue-600 before:absolute before:inset-0 before:z-1 dark:text-neutral-200 dark:hover:text-blue-500 dark:focus:text-blue-500",
                "dropdownClasses": "mt-2 z-50 w-20 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                "optionClasses": "p-2 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-gray-800 dark:text-neutral-200\" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>"
              }'
                            class="hidden">
                            <option selected>2023</option>
                            <option>2024</option>
                            <option>2025</option>
                            <option>2026</option>
                            <option>2027</option>
                        </select>
                    </div>
                </div>
                <!-- End Month / Year -->

                <!-- Next Button -->
                <div class="col-span-1 flex justify-end">
                    <button type="button"
                        class="size-8 flex justify-center items-center text-gray-800 hover:bg-gray-100 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        aria-label="Next">
                        <svg class="shrink-0 size-4" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
                <!-- End Next Button -->
            </div>
            <!-- Months -->

            <!-- Weeks -->
            <div class="flex pb-1.5">
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Mo
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Tu
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    We
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Th
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Fr
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Sa
                </span>
                <span class="m-px w-10 block text-center text-sm text-gray-500 dark:text-neutral-500">
                    Su
                </span>
            </div>
            <!-- Weeks -->

            <!-- Days -->
            <div class="flex">
                <div class="bg-linear-to-l from-gray-100 dark:from-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        31
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700">
                        1
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700">
                        2
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700">
                        3
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700">
                        4
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        5
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        6
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        7
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        8
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        9
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        10
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        11
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        12
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        13
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        14
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        15
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        16
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        17
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        18
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        19
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        20
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        21
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        22
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        23
                    </button>
                </div>
                <div class="bg-gray-100 first:rounded-s-full last:rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        24
                    </button>
                </div>
                <div class="bg-gray-100 rounded-e-full dark:bg-neutral-800">
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center bg-blue-600 border-[1.5px] border-transparent text-sm font-medium text-white hover:border-blue-600 rounded-full dark:bg-blue-500 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:hover:border-neutral-700">
                        25
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        26
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        27
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        28
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        29
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        30
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 rounded-full hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:border-blue-600 focus:text-blue-600 dark:text-neutral-200 dark:hover:border-blue-500 dark:hover:text-blue-500 dark:focus:border-blue-500 dark:focus:text-blue-500">
                        31
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        1
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        2
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        3
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Days -->
            <div class="flex">
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        4
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        5
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        6
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        7
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        8
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        9
                    </button>
                </div>
                <div>
                    <button type="button"
                        class="m-px size-10 flex justify-center items-center border-[1.5px] border-transparent text-sm text-gray-800 hover:border-blue-600 hover:text-blue-600 rounded-full disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-100 dark:text-neutral-200 dark:hover:border-neutral-500 dark:focus:bg-neutral-700"
                        disabled>
                        10
                    </button>
                </div>
            </div>
            <!-- Days -->

            <!-- Time -->
            <div class="pt-3 flex justify-center items-center gap-x-2">
                <!-- Select -->
                <div class="relative">
                    <select
                        data-hs-select='{
              "placeholder": "Select option...",
              "viewport": "#with-time-tab-preview-datepicker",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-1 px-2 pe-6 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
              "dropdownClasses": "mt-2 z-50 w-full min-w-24 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto dark:bg-neutral-900 dark:border-neutral-700",
              "dropdownVerticalFixedPlacement": "top",
              "optionClasses": "hs-selected:bg-gray-100 dark:hs-selected:bg-neutral-800 py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:hs-selected:bg-gray-700 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span></div>"
            }'
                        class="hidden">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option selected>12</option>
                    </select>

                    <div class="absolute top-1/2 end-2 -translate-y-1/2">
                        <svg class="shrink-0 size-3 text-gray-500 dark:text-neutral-500"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg>
                    </div>
                </div>
                <!-- End Select -->

                <span class="text-gray-800 dark:text-white">:</span>

                <!-- Select -->
                <div class="relative">
                    <select
                        data-hs-select='{
              "placeholder": "Select option...",
              "viewport": "#with-time-tab-preview-datepicker",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-1 px-2 pe-6 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
              "dropdownClasses": "mt-2 z-50 w-full min-w-24 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto dark:bg-neutral-900 dark:border-neutral-700",
              "dropdownVerticalFixedPlacement": "top",
              "optionClasses": "hs-selected:bg-gray-100 dark:hs-selected:bg-neutral-800 py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:hs-selected:bg-gray-700 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span></div>"
            }'
                        class="hidden">
                        <option selected>00</option>
                        <option>01</option>
                        <option>02</option>
                        <option>03</option>
                        <option>04</option>
                        <option>05</option>
                        <option>06</option>
                        <option>07</option>
                        <option>08</option>
                        <option>09</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                        <option>21</option>
                        <option>22</option>
                        <option>23</option>
                        <option>24</option>
                        <option>25</option>
                        <option>26</option>
                        <option>27</option>
                        <option>28</option>
                        <option>29</option>
                        <option>30</option>
                        <option>31</option>
                        <option>32</option>
                        <option>33</option>
                        <option>34</option>
                        <option>35</option>
                        <option>36</option>
                        <option>37</option>
                        <option>38</option>
                        <option>39</option>
                        <option>40</option>
                        <option>41</option>
                        <option>42</option>
                        <option>43</option>
                        <option>44</option>
                        <option>45</option>
                        <option>46</option>
                        <option>47</option>
                        <option>48</option>
                        <option>49</option>
                        <option>50</option>
                        <option>51</option>
                        <option>52</option>
                        <option>53</option>
                        <option>54</option>
                        <option>55</option>
                        <option>56</option>
                        <option>57</option>
                        <option>58</option>
                        <option>59</option>
                    </select>

                    <div class="absolute top-1/2 end-2 -translate-y-1/2">
                        <svg class="shrink-0 size-3 text-gray-500 dark:text-neutral-500"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg>
                    </div>
                </div>
                <!-- End Select -->

                <!-- Select -->
                <div class="relative">
                    <select
                        data-hs-select='{
              "placeholder": "Select option...",
              "viewport": "#with-time-tab-preview-datepicker",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-1 px-2 pe-6 flex text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400",
              "dropdownClasses": "mt-2 z-50 w-full min-w-24 max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto dark:bg-neutral-900 dark:border-neutral-700",
              "dropdownVerticalFixedPlacement": "top",
              "optionClasses": "hs-selected:bg-gray-100 dark:hs-selected:bg-neutral-800 py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:hs-selected:bg-gray-700 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span></div>"
            }'
                        class="hidden">
                        <option selected>PM</option>
                        <option>AM</option>
                    </select>

                    <div class="absolute top-1/2 end-2 -translate-y-1/2">
                        <svg class="shrink-0 size-3 text-gray-500 dark:text-neutral-500"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m7 15 5 5 5-5" />
                            <path d="m7 9 5-5 5 5" />
                        </svg>
                    </div>
                </div>
                <!-- End Select -->
            </div>
            <!-- End Time -->
        </div>
    </div>
    <!-- End Calendar -->

    <div
        class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 py-3 px-4 border-t border-gray-200 dark:border-neutral-700">
        <!-- Input Group -->
        <div class="flex justify-center md:justify-start items-center gap-x-2">
            <input type="text"
                class="p-2 block w-24 bg-gray-100 border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                value="20/07/2023">
            <span class="text-gray-800 dark:text-white">-</span>
            <input type="text"
                class="p-2 block w-24 bg-gray-100 border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                value="10/08/2023">
        </div>
        <!-- End Input Group -->

        <!-- Button Group -->
        <div class="flex items-center md:justify-end gap-x-2">

            <span class="md:me-3 text-xs text-gray-500 dark:text-neutral-500">
                20.07.2023 - 10.08.2023
            </span>
            <button type="button"
                class="py-2 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                Cancel
            </button>
            <button type="button"
                class="py-2 px-3  inline-flex justify-center items-center gap-x-2 text-xs font-medium rounded-lg border-[1.5px] border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                Apply
            </button>
        </div>
        <!-- End Button Group -->
    </div>
</div>
