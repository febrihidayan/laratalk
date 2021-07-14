<template>
    <section class="flex dark:text-gray-100">
        <aside
            :class="[`bg-white dark:bg-dark-300 sm:border-r-1 dark:sm:border-dark-200 h-screen fixed top-0 left-0 z-30 ease-in-out transition-all duration-300`, {
                'min-w-100 <sm:w-full': right_detail,
                '-left-full -left-100': !right_detail
            }]"
        >
            <div class="flex bg-blue-500 dark:bg-dark-400 text-white p-4">
                <a class="cursor-pointer" @click="right_detail = null">
                    <svg class="svg-icon !fill-white !stroke-white" viewBox="0 0 20 20">
                        <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                    </svg>
                </a>
                <p class="text-base ml-3">
                    {{ right_detail == 'profile' ? 'Profile' : 'New Chat' }}
                </p>
            </div>
            <div class="m-4">
                <template v-if="right_detail == 'profile'">
                    <figure class="my-8">
                        <img class="rounded-full h-48 w-48 mx-auto cursor-pointer" src="https://bulma.io/images/placeholders/128x128.png" alt="person">
                    </figure>
                </template>
            </div>
        </aside>
        <aside
            :class="[`flex-none min-w-100 sm:border-r-1 dark:sm:border-dark-200 <sm:w-full <lg:fixed <lg:top-0 <lg:left-0 <lg:z-20 ease-in-out transition-all duration-300`, {
                '<sm:-left-full <lg:-left-100': isRight
            }]"
        >
            <div class="flex bg-light-600 dark:bg-dark-400 px-5 py-2">
                <div class="flex-grow">
                    <img @click="right_detail = 'profile'" class="rounded-full h-10 w-10 cursor-pointer" src="https://bulma.io/images/placeholders/128x128.png" alt="person">
                </div>
                <div class="flex flex-grow-0 flex-shrink-0 my-auto">
                    <a class="cursor-pointer mx-2" @click="right_detail = 'chat'">
                        <svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M17.211,3.39H2.788c-0.22,0-0.4,0.18-0.4,0.4v9.614c0,0.221,0.181,0.402,0.4,0.402h3.206v2.402c0,0.363,0.429,0.533,0.683,0.285l2.72-2.688h7.814c0.221,0,0.401-0.182,0.401-0.402V3.79C17.612,3.569,17.432,3.39,17.211,3.39M16.811,13.004H9.232c-0.106,0-0.206,0.043-0.282,0.117L6.795,15.25v-1.846c0-0.219-0.18-0.4-0.401-0.4H3.189V4.19h13.622V13.004z"></path>
						</svg>
                    </a>
                    <a class="cursor-pointer mx-2" @click="dark_mode = false" v-if="dark_mode">
                        <svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M5.114,5.726c0.169,0.168,0.442,0.168,0.611,0c0.168-0.169,0.168-0.442,0-0.61L3.893,3.282c-0.168-0.168-0.442-0.168-0.61,0c-0.169,0.169-0.169,0.442,0,0.611L5.114,5.726z M3.955,10c0-0.239-0.193-0.432-0.432-0.432H0.932C0.693,9.568,0.5,9.761,0.5,10s0.193,0.432,0.432,0.432h2.591C3.761,10.432,3.955,10.239,3.955,10 M10,3.955c0.238,0,0.432-0.193,0.432-0.432v-2.59C10.432,0.693,10.238,0.5,10,0.5S9.568,0.693,9.568,0.932v2.59C9.568,3.762,9.762,3.955,10,3.955 M14.886,5.726l1.832-1.833c0.169-0.168,0.169-0.442,0-0.611c-0.169-0.168-0.442-0.168-0.61,0l-1.833,1.833c-0.169,0.168-0.169,0.441,0,0.61C14.443,5.894,14.717,5.894,14.886,5.726 M5.114,14.274l-1.832,1.833c-0.169,0.168-0.169,0.441,0,0.61c0.168,0.169,0.442,0.169,0.61,0l1.833-1.832c0.168-0.169,0.168-0.442,0-0.611C5.557,14.106,5.283,14.106,5.114,14.274 M19.068,9.568h-2.591c-0.238,0-0.433,0.193-0.433,0.432s0.194,0.432,0.433,0.432h2.591c0.238,0,0.432-0.193,0.432-0.432S19.307,9.568,19.068,9.568 M14.886,14.274c-0.169-0.168-0.442-0.168-0.611,0c-0.169,0.169-0.169,0.442,0,0.611l1.833,1.832c0.168,0.169,0.441,0.169,0.61,0s0.169-0.442,0-0.61L14.886,14.274z M10,4.818c-2.861,0-5.182,2.32-5.182,5.182c0,2.862,2.321,5.182,5.182,5.182s5.182-2.319,5.182-5.182C15.182,7.139,12.861,4.818,10,4.818M10,14.318c-2.385,0-4.318-1.934-4.318-4.318c0-2.385,1.933-4.318,4.318-4.318c2.386,0,4.318,1.933,4.318,4.318C14.318,12.385,12.386,14.318,10,14.318 M10,16.045c-0.238,0-0.432,0.193-0.432,0.433v2.591c0,0.238,0.194,0.432,0.432,0.432s0.432-0.193,0.432-0.432v-2.591C10.432,16.238,10.238,16.045,10,16.045"></path>
						</svg>
                    </a>
                    <a class="cursor-pointer mx-2" @click="dark_mode = true" v-else>
                        <svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M10.544,8.717l1.166-0.855l1.166,0.855l-0.467-1.399l1.012-0.778h-1.244L11.71,5.297l-0.466,1.244H10l1.011,0.778L10.544,8.717z M15.986,9.572l-0.467,1.244h-1.244l1.011,0.777l-0.467,1.4l1.167-0.855l1.165,0.855l-0.466-1.4l1.011-0.777h-1.244L15.986,9.572z M7.007,6.552c0-2.259,0.795-4.33,2.117-5.955C4.34,1.042,0.594,5.07,0.594,9.98c0,5.207,4.211,9.426,9.406,9.426c2.94,0,5.972-1.354,7.696-3.472c-0.289,0.026-0.987,0.044-1.283,0.044C11.219,15.979,7.007,11.759,7.007,6.552 M10,18.55c-4.715,0-8.551-3.845-8.551-8.57c0-3.783,2.407-6.999,5.842-8.131C6.549,3.295,6.152,4.911,6.152,6.552c0,5.368,4.125,9.788,9.365,10.245C13.972,17.893,11.973,18.55,10,18.55 M19.406,2.304h-1.71l-0.642-1.71l-0.642,1.71h-1.71l1.39,1.069l-0.642,1.924l1.604-1.176l1.604,1.176l-0.642-1.924L19.406,2.304z"></path>
						</svg>
                    </a>
                    <a class="cursor-pointer mx-2">
                        <svg class="svg-icon transform rotate-90" viewBox="0 0 20 20">
							<path d="M3.936,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021S5.957,11.116,5.957,10 S5.052,7.979,3.936,7.979z M3.936,11.011c-0.558,0-1.011-0.452-1.011-1.011s0.453-1.011,1.011-1.011S4.946,9.441,4.946,10 S4.494,11.011,3.936,11.011z M16.064,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021s2.021-0.905,2.021-2.021 S17.181,7.979,16.064,7.979z M16.064,11.011c-0.559,0-1.011-0.452-1.011-1.011s0.452-1.011,1.011-1.011S17.075,9.441,17.075,10 S16.623,11.011,16.064,11.011z M10,7.979c-1.116,0-2.021,0.905-2.021,2.021S8.884,12.021,10,12.021s2.021-0.905,2.021-2.021 S11.116,7.979,10,7.979z M10,11.011c-0.558,0-1.011-0.452-1.011-1.011S9.442,8.989,10,8.989S11.011,9.441,11.011,10 S10.558,11.011,10,11.011z"></path>
						</svg>
                    </a>
                    <a class="cursor-pointer mx-2 -mr-2 lg:hidden" @click="isRight = !isRight">
                        <svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
						</svg>
                    </a>
                </div>
            </div>
            <div class="bg-light-200 dark:bg-dark-200 border-b-1 dark:border-dark-50 px-4 py-2">
                <div class="flex rounded-full bg-white dark:bg-dark-50 p-1">
                    <span class="flex-grow-0 flex-shrink-0">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path d="M18.125,15.804l-4.038-4.037c0.675-1.079,1.012-2.308,1.01-3.534C15.089,4.62,12.199,1.75,8.584,1.75C4.815,1.75,1.982,4.726,2,8.286c0.021,3.577,2.908,6.549,6.578,6.549c1.241,0,2.417-0.347,3.44-0.985l4.032,4.026c0.167,0.166,0.43,0.166,0.596,0l1.479-1.478C18.292,16.234,18.292,15.968,18.125,15.804 M8.578,13.99c-3.198,0-5.716-2.593-5.733-5.71c-0.017-3.084,2.438-5.686,5.74-5.686c3.197,0,5.625,2.493,5.64,5.624C14.242,11.548,11.621,13.99,8.578,13.99 M16.349,16.981l-3.637-3.635c0.131-0.11,0.721-0.695,0.876-0.884l3.642,3.639L16.349,16.981z"></path>
                        </svg>
                    </span>
                    <input type="search" class="flex-grow dark:bg-dark-50 focus:outline-none ml-3" placeholder="Search">
                </div>
            </div>
            <div class="sidebar-content bg-white dark:bg-dark-300 overflow-y-auto overflow-x-hidden divide-y dark:divide-gray-700 divide-light-500">
                <div
                    v-for="(item, index) in users"
                    :key="index"
                    @click="fetchMessages(item.id);isRight = true"
                    :title="item.content"
                    :class="[`sidebar-list flex h-18 cursor-pointer`, {
                        'font-medium': item.read_count,
                        'hover:bg-light-300 dark:hover:bg-true-gray-700': form.to_id != item.id,
                        'bg-light-500 dark:bg-true-gray-800': form.to_id == item.id
                    }]"
                >
                    <div class="flex flex-col justify-center px-3">
                        <figure>
                            <img class="rounded-full h-13 w-13" src="https://bulma.io/images/placeholders/128x128.png" alt="person">
                        </figure>
                    </div>
                    <div class="flex flex-grow flex-col justify-center w-73 pr-3">
                        <div class="flex">
                            <p class="flex-grow truncate text-lg">{{
                                item.name
                            }}</p>
                            <small class="flex-none my-auto">Yesterday</small>
                        </div>
                        <div class="flex text-sm">
                            <template
                                v-if="item.id != item.content_by"
                            >
                                <svg class="svg-icon svg-sm flex-none" viewBox="0 0 20 20">
                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                                <svg class="svg-icon svg-sm flex-none -ml-3" viewBox="0 0 20 20">
                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                </svg>
                            </template>
                            <span class="flex-grow truncate">{{
                                item.content
                            }}</span>
                            <small
                                v-if="item.read_count"
                                class="flex-none bg-purple-600 text-white w-5 h-5 leading-5 text-center rounded-full"
                            >{{
                                item.read_count
                            }}</small>
                            <div class="button-dropdown flex-none relative font-normal -mr-9 pl-3 transition-effect">
                                <a class="cursor-pointer" aria-haspopup="true" aria-controls="dropdown-menu">
                                    <svg class="svg-icon transform -rotate-90" viewBox="0 0 20 20">
                                        <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                    </svg>
                                </a>
                                <div class="bg-white hidden dark:bg-dark-300 absolute right-0 mt-5 py-2 w-48 rounded-md shadow-lg" id="dropdown-menu" role="menu">
                                    <a href="#" class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-light-700 dark:hover:bg-dark-100 dark:hover:text-light-100 block px-4 py-2 text-sm">Delete Chat</a>
                                    <a href="#" class="text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-light-700 dark:hover:bg-dark-100 dark:hover:text-light-100 block px-4 py-2 text-sm">Exit Group</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <main class="flex-grow z-10">
            <div class="bg-light-600 dark:bg-dark-400 px-5 py-2 <lg:px-2">
                <div class="flex">
                    <div class="flex-none mr-3 my-auto lg:hidden">
                        <a class="cursor-pointer" @click="isRight = !isRight">
                            <svg class="svg-icon" viewBox="0 0 20 20">
                                <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="flex-grow-0 flex-shrink-0 cursor-pointer" @click="isDetail = true">
                        <img class="rounded-full h-10 w-10" src="https://bulma.io/images/placeholders/128x128.png" alt="person">
                    </div>
                    <div class="flex-grow cursor-pointer ml-4 my-auto" @click="isDetail = true">
                        <p class="text-base leading-none">{{
                            message.name
                        }}</p>
                        <!-- <small class="text-sm leading-none"></small> -->
                    </div>
                    <div class="flex flex-grow-0 flex-shrink-0 my-auto">
                        <a class="cursor-pointer mx-2">
                            <svg class="svg-icon transform rotate-90" viewBox="0 0 20 20">
                                <path d="M3.936,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021S5.957,11.116,5.957,10 S5.052,7.979,3.936,7.979z M3.936,11.011c-0.558,0-1.011-0.452-1.011-1.011s0.453-1.011,1.011-1.011S4.946,9.441,4.946,10 S4.494,11.011,3.936,11.011z M16.064,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021s2.021-0.905,2.021-2.021 S17.181,7.979,16.064,7.979z M16.064,11.011c-0.559,0-1.011-0.452-1.011-1.011s0.452-1.011,1.011-1.011S17.075,9.441,17.075,10 S16.623,11.011,16.064,11.011z M10,7.979c-1.116,0-2.021,0.905-2.021,2.021S8.884,12.021,10,12.021s2.021-0.905,2.021-2.021 S11.116,7.979,10,7.979z M10,11.011c-0.558,0-1.011-0.452-1.011-1.011S9.442,8.989,10,8.989S11.011,9.441,11.011,10 S10.558,11.011,10,11.011z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="main-content bg-light-200 dark:bg-dark-100 flex flex-col overflow-y-auto px-24 <sm:px-5 <md:px-10" id="main-content">
                <template v-for="(item, index) in message.messages" :key="index">
                    <div
                        v-if="message.id == item.content_by"
                        class="message bg-white dark:text-dark-300 grid rounded-r-2xl rounded-t-2xl shadow-lg shadow-dark-100 dark:shadow-light-100 max-w-66/100 my-4 p-3 mr-auto"
                    >
                        <div class="relative">
                            <a class="button-dropdown cursor-pointer absolute w-10 bg-gradient-to-tr from-white/80 to-white right-0 opacity-0 transition-effect">
                                <svg class="svg-icon !bg-white !fill-gray-400 !stroke-gray-400 float-right transform -rotate-90" viewBox="0 0 20 20">
                                    <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                </svg>
                            </a>
                        </div>
                        <p>{{
                            item.content
                        }}</p>
                        <small class="text-right">20:30</small>
                    </div>
                    <div
                        v-else
                        class="message bg-dark-500 text-white rounded-t-2xl rounded-l-2xl shadow-lg shadow-dark-100 dark:shadow-light-100 max-w-66/100 my-4 p-3 ml-auto"
                    >
                        <div class="relative">
                            <a class="button-dropdown cursor-pointer absolute w-10 bg-gradient-to-tr from-dark-500/80 to-dark-500 right-0 opacity-0 transition-effect">
                                <svg class="svg-icon !bg-dark-500 float-right transform -rotate-90" viewBox="0 0 20 20">
                                    <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                </svg>
                            </a>
                        </div>
                        <p>{{
                            item.content
                        }}</p>
                        <small class="flex float-right">
                            20:34
                            <svg class="svg-icon svg-sm" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                            <svg class="svg-icon svg-sm -ml-4" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                            </svg>
                        </small>
                    </div>
                </template>
            </div>
            <div class="bg-light-600 dark:bg-dark-500 px-5 py-2">
                <div class="rounded-full bg-white dark:bg-dark-200 mx-auto px-4 py-2">
                    <textarea class="dark:bg-dark-200 focus:outline-none w-full h-6 break-words border-none resize-none" placeholder="Type a message"></textarea>
                </div>
            </div>
        </main>
        <aside :class="[`flex-none bg-white dark:bg-dark-300 w-0 h-screen <xl:fixed <xl:top-0 <xl:right-0 z-10 ease-in-out transition-all duration-300`, {
            'w-100 <sm:w-full md:border-l-1 dark:md:border-dark-200': isDetail
        }]">
            <div class="flex bg-blue-500 dark:bg-dark-400 text-white p-4">
                <a class="cursor-pointer" @click="isDetail = !isDetail">
                    <svg class="svg-icon !fill-white !stroke-white" viewBox="0 0 20 20">
                        <path d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                    </svg>
                </a>
                <p class="text-base ml-3">Contact Info</p>
            </div>
        </aside>
    </section>
</template>

<script>
import { debounce } from 'lodash'

export default {
    data() {
        return {
            laratalk: window.Laratalk,
            dark_mode: false,
            right_detail: null,
            isRight: false,
            isDetail: false,
            isChat: false,
            userIndex: null,
            search: '',
            users: [],
            message: {},
            profile: {},
            form: {
                from_id: window.Laratalk.user_id,
                to_id: '',
                content: ''
            }
        }
    },
    
    methods: {

        fetchUsers()
        {
            let q = this.search || 'all'

            axios.get('user/' + q).then(({ data }) => {
                this.users = data
            })
        },

        fetchMessages(id)
        {
            this.form.to_id = id

            axios.get('message/' + id).then(({ data }) => {
                this.message = data

                let index = this.users.findIndex((s) => s.id === id)
                this.users[index].read_count = 0
            })
        },

        pushMessage(data, user_id, type = '')
        {
            let index = this.users.findIndex((s) => s.id === user_id)

            if (index != -1 && type == 'push') {
                this.users.splice(index, 1)
            }

            /**
             * if untuk pesan submit
             */
            if (type == '') {
                this.users[index].content = data.content
                this.users[index].to_id = data.to_id

                let user = this.users[index]

                this.users.splice(index, 1)
                this.users.unshift(user)
            }

            /**
             * else untuk pesan dari laravel echo
             */
            else {
                this.users.unshift(data)
            }

            /**
             * Jika dia melihat pesan user
             */
            if (this.form.to_id != '') {
                index = this.users.findIndex((s) => s.id === this.form.to_id)

                this.users[index].count = 0
                this.userIndex = index

                if (this.form.to_id == user_id) {

                    this.messages.push({
                        avatar: data.avatar,
                        content: data.content,
                        created_at: data.created_at,
                        from_id: data.from_id,
                    })

                    axios.put('/message/' + user_id)
    
                }

            }
        },

        sendMessage()
        {
            axios.post('message', this.form).then(({ data }) => {
                this.form.content = ''
                this.search = ''

                this.pushMessage(data, data.to_id)
            })
        },

        fetchEcho()
        {
            Echo.channel('laratalk-user.' + window.Laratalk.user_id)
                .listen('MessageEvent', (e) => {
                    this.pushMessage(e, e.from_id, 'push')
                })
        },

        scrollToEnd()
        {
            let container = this.$el.querySelector("#main-content")
            container.scrollTop = container.scrollHeight
        }

    },

    mounted() {
        this.fetchUsers()
        this.fetchEcho()
    },

    watch: {
        search: debounce( function() {
            this.fetchUsers()
        }, 500),

        messages: debounce( function() {
            this.scrollToEnd()
        }, 10),
        dark_mode: function(e) {
            if (e) {
                document.documentElement.classList.add('dark')
            }
            else {
                document.documentElement.classList.remove('dark')
            }
        }
    }
}
</script>