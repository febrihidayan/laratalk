<template>
    <section class="flex dark:bg-dark-100 dark:text-gray-100">
        <aside
            :class="[`bg-white dark:bg-dark-300 sm:border-r-1 dark:sm:border-dark-200 fixed top-0 left-0 z-30 ease-in-out transition-all duration-300`, {
                'min-w-100 <sm:w-full': left_detail,
                '-left-full -left-100': !left_detail
            }]"
        >
            <div class="flex bg-blue-500 dark:bg-dark-400 text-white p-4">
                <a class="cursor-pointer" @click="resetLeft">
                    <svg class="svg-icon !fill-white !stroke-white" viewBox="0 0 20 20">
                        <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                    </svg>
                </a>
                <p class="text-base ml-3">
                    {{ left_detail == 'profile' ? trans.profile : trans.new_chat }}
                </p>
            </div>
            <template v-if="left_detail == 'profile'">
                <div class="sidebar-detail bg-light-600 dark:bg-dark-100">
                    <div class="dark:bg-dark-300 py-8">
                        <img
                            class="rounded-full h-48 w-48 mx-auto cursor-pointer"
                            :src="laratalk.profile.avatar"
                            alt="avatar"
                        >
                    </div>
                    <div class="bg-white dark:bg-dark-300 my-2 px-6 py-4">
                        <small class="text-purple-800 dark:text-purple-300">{{
                            trans.your_name
                        }}</small>
                        <div class="mt-4">
                            <p>{{
                                laratalk.profile.name
                            }}</p>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="left_detail == 'chat'">
                <div class="bg-light-200 dark:bg-dark-200 border-b-1 dark:border-dark-50 px-4 py-2">
                    <div class="flex rounded-full bg-white dark:bg-dark-50 p-1">
                        <span class="flex-grow-0 flex-shrink-0">
                            <svg class="svg-icon" viewBox="0 0 20 20">
                                <path d="M18.125,15.804l-4.038-4.037c0.675-1.079,1.012-2.308,1.01-3.534C15.089,4.62,12.199,1.75,8.584,1.75C4.815,1.75,1.982,4.726,2,8.286c0.021,3.577,2.908,6.549,6.578,6.549c1.241,0,2.417-0.347,3.44-0.985l4.032,4.026c0.167,0.166,0.43,0.166,0.596,0l1.479-1.478C18.292,16.234,18.292,15.968,18.125,15.804 M8.578,13.99c-3.198,0-5.716-2.593-5.733-5.71c-0.017-3.084,2.438-5.686,5.74-5.686c3.197,0,5.625,2.493,5.64,5.624C14.242,11.548,11.621,13.99,8.578,13.99 M16.349,16.981l-3.637-3.635c0.131-0.11,0.721-0.695,0.876-0.884l3.642,3.639L16.349,16.981z"></path>
                            </svg>
                        </span>
                        <input
                            type="search"
                            class="flex-grow dark:bg-dark-50 focus:outline-none ml-3" 
                            :placeholder="trans.search"
                            autofocus
                            @input="fetchSearchNewChat"
                        >
                    </div>
                </div>
                <div class="sidebar-content bg-white dark:bg-dark-300 dark:divide-gray-700 divide-light-500">
                    <template
                        v-for="(item, index) in users_new_chat"
                        :key="index"
                    >
                        <div
                            v-if="index == 0 || (users_new_chat[index-1] && users_new_chat[index-1].name.substr(0,1) != item.name.substr(0,1))"
                            class="flex flex-grow mx-8 my-6 text-violet-600 dark:text-light-200"
                        >{{
                            item.name.substr(0,1)
                        }}</div>
                        <div
                            @click="fetchMessages(item.id, models.message.type_user);resetLeft()"
                            class="sidebar-list flex h-18 cursor-pointer hover:bg-light-300 dark:hover:bg-true-gray-700"
                        >
                            <div class="flex flex-col justify-center px-3">
                                <figure>
                                    <img
                                        class="rounded-full h-13 w-13"
                                        :src="item.avatar"
                                        alt="avatar"
                                    >
                                </figure>
                            </div>
                            <div class="flex flex-grow flex-col justify-center w-73 pr-3">
                                <div class="flex">
                                    <p class="flex-grow truncate text-lg">{{
                                        item.name
                                    }}</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </aside>
        <aside
            :class="[`flex-none min-w-100 sm:border-r-1 dark:sm:border-dark-200 <sm:w-full <lg:fixed <lg:top-0 <lg:left-0 <lg:z-20 ease-in-out transition-all duration-300`, {
                '<sm:-left-full <lg:-left-100': isRight
            }]"
        >
            <div class="flex bg-light-600 dark:bg-dark-400 px-5 py-2">
                <div class="flex-grow">
                    <img
                        @click="left_detail = 'profile'"
                        class="rounded-full h-10 w-10 cursor-pointer"
                        :src="laratalk.profile.avatar"
                        alt="profile"
                    >
                </div>
                <div class="flex flex-grow-0 flex-shrink-0 my-auto">
                    <a class="cursor-pointer mx-2" @click="left_detail = 'chat'">
                        <svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M17.211,3.39H2.788c-0.22,0-0.4,0.18-0.4,0.4v9.614c0,0.221,0.181,0.402,0.4,0.402h3.206v2.402c0,0.363,0.429,0.533,0.683,0.285l2.72-2.688h7.814c0.221,0,0.401-0.182,0.401-0.402V3.79C17.612,3.569,17.432,3.39,17.211,3.39M16.811,13.004H9.232c-0.106,0-0.206,0.043-0.282,0.117L6.795,15.25v-1.846c0-0.219-0.18-0.4-0.401-0.4H3.189V4.19h13.622V13.004z"></path>
						</svg>
                    </a>
                    <a class="cursor-pointer mx-2" @click="setDarkMode()">
                        <svg class="svg-icon" viewBox="0 0 20 20">
							<path v-if="dark_mode" d="M5.114,5.726c0.169,0.168,0.442,0.168,0.611,0c0.168-0.169,0.168-0.442,0-0.61L3.893,3.282c-0.168-0.168-0.442-0.168-0.61,0c-0.169,0.169-0.169,0.442,0,0.611L5.114,5.726z M3.955,10c0-0.239-0.193-0.432-0.432-0.432H0.932C0.693,9.568,0.5,9.761,0.5,10s0.193,0.432,0.432,0.432h2.591C3.761,10.432,3.955,10.239,3.955,10 M10,3.955c0.238,0,0.432-0.193,0.432-0.432v-2.59C10.432,0.693,10.238,0.5,10,0.5S9.568,0.693,9.568,0.932v2.59C9.568,3.762,9.762,3.955,10,3.955 M14.886,5.726l1.832-1.833c0.169-0.168,0.169-0.442,0-0.611c-0.169-0.168-0.442-0.168-0.61,0l-1.833,1.833c-0.169,0.168-0.169,0.441,0,0.61C14.443,5.894,14.717,5.894,14.886,5.726 M5.114,14.274l-1.832,1.833c-0.169,0.168-0.169,0.441,0,0.61c0.168,0.169,0.442,0.169,0.61,0l1.833-1.832c0.168-0.169,0.168-0.442,0-0.611C5.557,14.106,5.283,14.106,5.114,14.274 M19.068,9.568h-2.591c-0.238,0-0.433,0.193-0.433,0.432s0.194,0.432,0.433,0.432h2.591c0.238,0,0.432-0.193,0.432-0.432S19.307,9.568,19.068,9.568 M14.886,14.274c-0.169-0.168-0.442-0.168-0.611,0c-0.169,0.169-0.169,0.442,0,0.611l1.833,1.832c0.168,0.169,0.441,0.169,0.61,0s0.169-0.442,0-0.61L14.886,14.274z M10,4.818c-2.861,0-5.182,2.32-5.182,5.182c0,2.862,2.321,5.182,5.182,5.182s5.182-2.319,5.182-5.182C15.182,7.139,12.861,4.818,10,4.818M10,14.318c-2.385,0-4.318-1.934-4.318-4.318c0-2.385,1.933-4.318,4.318-4.318c2.386,0,4.318,1.933,4.318,4.318C14.318,12.385,12.386,14.318,10,14.318 M10,16.045c-0.238,0-0.432,0.193-0.432,0.433v2.591c0,0.238,0.194,0.432,0.432,0.432s0.432-0.193,0.432-0.432v-2.591C10.432,16.238,10.238,16.045,10,16.045"></path>
							<path v-else d="M10.544,8.717l1.166-0.855l1.166,0.855l-0.467-1.399l1.012-0.778h-1.244L11.71,5.297l-0.466,1.244H10l1.011,0.778L10.544,8.717z M15.986,9.572l-0.467,1.244h-1.244l1.011,0.777l-0.467,1.4l1.167-0.855l1.165,0.855l-0.466-1.4l1.011-0.777h-1.244L15.986,9.572z M7.007,6.552c0-2.259,0.795-4.33,2.117-5.955C4.34,1.042,0.594,5.07,0.594,9.98c0,5.207,4.211,9.426,9.406,9.426c2.94,0,5.972-1.354,7.696-3.472c-0.289,0.026-0.987,0.044-1.283,0.044C11.219,15.979,7.007,11.759,7.007,6.552 M10,18.55c-4.715,0-8.551-3.845-8.551-8.57c0-3.783,2.407-6.999,5.842-8.131C6.549,3.295,6.152,4.911,6.152,6.552c0,5.368,4.125,9.788,9.365,10.245C13.972,17.893,11.973,18.55,10,18.55 M19.406,2.304h-1.71l-0.642-1.71l-0.642,1.71h-1.71l1.39,1.069l-0.642,1.924l1.604-1.176l1.604,1.176l-0.642-1.924L19.406,2.304z"></path>
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

            <!-- column search -->
            <div class="bg-light-200 dark:bg-dark-200 border-b-1 dark:border-dark-50 px-4 py-2">
                <div class="flex rounded-full bg-white dark:bg-dark-50 p-1">
                    <span class="flex-grow-0 flex-shrink-0">
                        <svg class="svg-icon" viewBox="0 0 20 20">
                            <path d="M18.125,15.804l-4.038-4.037c0.675-1.079,1.012-2.308,1.01-3.534C15.089,4.62,12.199,1.75,8.584,1.75C4.815,1.75,1.982,4.726,2,8.286c0.021,3.577,2.908,6.549,6.578,6.549c1.241,0,2.417-0.347,3.44-0.985l4.032,4.026c0.167,0.166,0.43,0.166,0.596,0l1.479-1.478C18.292,16.234,18.292,15.968,18.125,15.804 M8.578,13.99c-3.198,0-5.716-2.593-5.733-5.71c-0.017-3.084,2.438-5.686,5.74-5.686c3.197,0,5.625,2.493,5.64,5.624C14.242,11.548,11.621,13.99,8.578,13.99 M16.349,16.981l-3.637-3.635c0.131-0.11,0.721-0.695,0.876-0.884l3.642,3.639L16.349,16.981z"></path>
                        </svg>
                    </span>
                    <input
                        type="search"
                        class="flex-grow dark:bg-dark-50 focus:outline-none ml-3" 
                        :placeholder="trans.search"
                        @input="fetchSearchUserChat"
                    >
                </div>
            </div>

            <!-- list users and groups -->
            <div class="sidebar-content bg-white dark:bg-dark-300 dark:divide-gray-700 divide-light-500">
                <div
                    v-for="(item, index) in users"
                    :key="index"
                    @click="fetchMessages(item.group_id || item.id, item.chat_type);form.active = index"
                    :class="[`sidebar-list flex h-18 cursor-pointer`, {
                        'font-medium': item.read_count,
                        'hover:bg-light-300 dark:hover:bg-true-gray-700':
                            form.active != index,
                        'bg-light-500 dark:bg-true-gray-800':
                            form.active == index
                    }]"
                >
                    <div class="flex flex-col justify-center px-3">
                        <figure>
                            <img
                                class="rounded-full h-13 w-13"
                                :src="item.avatar"
                                alt="avatar"
                            >
                        </figure>
                    </div>
                    <div class="flex flex-grow flex-col justify-center w-73 pr-3">
                        <div class="flex">
                            <p
                                class="flex-grow truncate text-lg"
                                :title="item.name"
                            >{{
                                item.name
                            }}</p>
                            <small class="flex-none my-auto">{{
                                item.last_time
                            }}</small>
                        </div>
                        <div class="flex text-sm">
                            <span v-if="item.typing" class="flex-grow">{{
                                trans.typing
                            }}</span>
                            <template v-else>
                                <div
                                    class="flex flex-grow"
                                    :title="item.content"
                                >
                                    <template
                                        v-if="laratalk.profile.id === item.content_by && item.content_type === models.message.chat"
                                    >
                                        <svg
                                            :class="[`svg-icon svg-sm flex-none`, {
                                                '!fill-dark-200 !stroke-dark-200 dark:!fill-light-200 dark:!stroke-light-200': item.status != 'read'
                                            }]"
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                        </svg>
                                        <svg
                                            v-if="item.status != models.message.send"
                                            :class="[`svg-icon svg-sm flex-none -ml-4`, {
                                                '!fill-dark-200 !stroke-dark-200 dark:!fill-light-200 dark:!stroke-light-200': item.status == 'accept'
                                            }]"
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                        </svg>
                                    </template>
                                    <span class="flex-grow truncate">{{
                                        item.chat_type === models.message.type_user
                                            ? item.content : getTransMessage(item)
                                    }}</span>
                                </div>
                            </template>
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
        <main
            :class="[`flex-grow z-10`, {
                'flex': !message.messages
            }]"
        >
            <template v-if="message.messages">
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
                            <img
                                class="rounded-full h-10 w-10"
                                :src="message.avatar"
                                alt="avatar"
                            >
                        </div>
                        <div class="flex-grow cursor-pointer ml-4 my-auto" @click="isDetail = true">
                            <p class="text-base leading-none">{{
                                message.name
                            }}</p>
                            <small v-if="message.typing" class="text-sm leading-none">{{
                                trans.typing
                            }}</small>
                            <small
                                v-if="message.online && !message.typing && message.chat_type === models.message.type_user"
                                class="text-sm leading-none"
                            >{{
                                trans.online
                            }}</small>
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
                <div class="main-content bg-light-200 dark:bg-dark-100 flex flex-col px-24 <sm:px-5 <md:px-10" id="main-content">
                    <template
                        v-for="(item, index) in message.messages"
                        :key="index"
                    >
                        <div
                            v-if="index == 0 || (message.messages[index-1] && message.messages[index-1].last_time != item.last_time)"
                            class="bg-light-400 text-dark-500 rounded-md mx-auto my-4 py-1 px-2"
                        >
                            <span class="uppercase text-xs">{{
                                item.last_time
                            }}</span>
                        </div>
                        <div
                            v-if="item.content_type !== models.message.chat && item.chat_type === models.message.type_group"
                            class="bg-light-400 text-dark-500 rounded-md mx-auto my-4 py-1 px-2"
                        >
                            <span class="text-xs">{{
                                getTransMessage(item)
                            }}</span>
                        </div>
                        <template v-else>

                            <!-- message right -->
                            <div
                                v-if="laratalk.profile.id == item.content_by"
                                class="message bg-dark-500 text-white rounded-t-xl rounded-l-xl shadow-lg shadow-dark-100 dark:shadow-light-100 max-w-66/100 my-4 p-3 ml-auto"
                            >
                                <div class="relative z-20">
                                    <a class="button-dropdown cursor-pointer absolute w-10 bg-gradient-to-tr from-dark-500/80 to-dark-500 right-0 opacity-0 transition-effect">
                                        <svg class="svg-icon !bg-dark-500 float-right transform -rotate-90" viewBox="0 0 20 20">
                                            <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                        </svg>
                                    </a>
                                </div>
                                <p>
                                    <span class="whitespace-pre-wrap">{{
                                        item.content
                                    }}</span>
                                    <span class="w-18 inline-block"></span>
                                </p>
                                <small class="flex float-right -mt-3 -mb-5px z-30">{{
                                    item.time
                                }}
                                    <svg
                                        :class="[`svg-icon svg-sm`, {
                                            '!fill-light-200 !stroke-light-200': item.status != 'read'
                                        }]"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                    </svg>
                                    <svg
                                        v-if="item.status != 'send'"
                                        :class="[`svg-icon svg-sm -ml-4`, {
                                            '!fill-light-200 !stroke-light-200': item.status == 'accept'
                                        }]"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                                    </svg>
                                </small>
                            </div>
                            
                            <!-- message left -->
                            <div
                                v-else
                                class="message bg-white dark:text-dark-300 rounded-r-xl rounded-t-xl shadow-lg shadow-dark-100 dark:shadow-light-100 max-w-66/100 my-4 p-2 mr-auto"
                            >
                                <div class="relative z-20">
                                    <a class="button-dropdown cursor-pointer absolute w-10 bg-gradient-to-tr from-white/80 to-white right-0 opacity-0 transition-effect">
                                        <svg class="svg-icon !bg-white !fill-gray-400 !stroke-gray-400 float-right transform -rotate-90" viewBox="0 0 20 20">
                                            <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                        </svg>
                                    </a>
                                </div>
                                <a
                                    @click="fetchMessages(item.user_by.id, models.message.type_user)"
                                    v-if="item.chat_type === models.message.type_group"
                                    class="cursor-pointer !text-cyan-500 hover:underline"
                                >{{
                                    item.user_by.name
                                }}</a>
                                <p>
                                    <span class="whitespace-pre-wrap">{{
                                        item.content
                                    }}</span>
                                    <span class="w-13 inline-block"></span>
                                </p>
                                <small class="float-right -mt-3 -mb-5px z-30">{{
                                    item.time
                                }}</small>
                            </div>
                        </template>
                    </template>
                </div>
                <div class="bg-light-600 dark:bg-dark-500 px-5 py-2">
                    <div class="rounded-full bg-white dark:bg-dark-200 mx-auto px-4 py-2">
                        <textarea 
                            v-model="form.content"
                            class="dark:bg-dark-200 focus:outline-none w-full h-6 break-words border-none resize-none"
                            :placeholder="trans.type_a_message"
                            @keydown="isTyping"
                            @keyup.enter="sendMessage"
                        />
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="flex flex-grow flex-col justify-center text-center h-screen overflow-y-auto">
                    <h1 class="font-semibold text-3xl">{{
                        trans.laratalk_title
                    }}</h1>
                    <p class="my-3">{{
                        trans.laratalk_description
                    }}</p>
                </div>
            </template>
        </main>
        <aside :class="[`flex-none bg-light-600 dark:bg-dark-100 w-0 <xl:fixed <xl:top-0 <xl:right-0 z-10 ease-in-out transition-all duration-300`, {
            'w-100 <sm:w-full md:border-l-1 dark:md:border-dark-200': isDetail
        }]">
            <div class="flex bg-blue-500 dark:bg-dark-400 text-white p-4">
                <a class="cursor-pointer" @click="isDetail = !isDetail">
                    <svg class="svg-icon !fill-white !stroke-white" viewBox="0 0 20 20">
                        <path d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                    </svg>
                </a>
                <p class="text-base ml-3">{{
                    trans.contact_info
                }}</p>
            </div>
            <div class="sidebar-detail">
                <div class="bg-white dark:bg-dark-300 p-6">
                    <figure class="my-4">
                        <img
                            class="rounded-full h-48 w-48 mx-auto cursor-pointer"
                            :src="message.avatar"
                            alt="avatar"
                        >
                    </figure>
                    <p class="text-xl">{{
                        message.name
                    }}</p>
                    <small v-if="message.chat_type === models.message.type_group">{{
                        `${trans.made} ${message.last_time} ${trans.at} ${message.time}`
                    }}</small>
                </div>
                <div class="bg-white dark:bg-dark-300 my-2 px-6 py-4">
                    <small class="text-purple-800 dark:text-purple-300">{{
                        message.chat_type === models.message.type_user
                            ? trans.info_and_email_address
                            : trans.description
                    }}</small>
                    <div class="mt-4">
                        <p>{{
                            message.chat_type === models.message.type_user
                                ? message.email : message.description
                        }}</p>
                    </div>
                </div>
                <div
                    v-if="message.chat_type === models.message.type_group"
                    class="bg-white dark:bg-dark-300 my-2 py-4"
                >
                    <small class="text-purple-800 dark:text-purple-300 px-6">{{
                        message.users.length + ` ${trans.participants}`
                    }}</small>
                    <div class="mt-4">
                        <template
                                v-for="(item, index) in message.users"
                                :key="index"
                            >
                                <div
                                    v-on:click="laratalk.profile.id != item.id ? fetchMessages(item.id, models.message.type_user) : ''"
                                    :class="[`sidebar-list flex h-18`, {
                                        'cursor-pointer hover:bg-light-300 dark:hover:bg-true-gray-700': laratalk.profile.id != item.id
                                    }]"
                                >
                                    <div class="flex flex-col justify-center px-3 pl-6">
                                        <figure>
                                            <img
                                                class="rounded-full h-13 w-13"
                                                :src="item.avatar"
                                                alt="avatar"
                                            >
                                        </figure>
                                    </div>
                                    <div class="flex flex-grow flex-col justify-center w-73 pr-6">
                                        <div class="flex">
                                            <p class="flex-grow truncate text-lg">{{
                                                laratalk.profile.id != item.id
                                                    ? item.name : trans.you
                                            }}</p>
                                            <small
                                                v-if="item.role === models.group.admin"
                                                class="flex-none bg-violet-600 text-white text-xs py-1 px-2 rounded-md"
                                            >{{
                                                trans.group_admin
                                            }}</small>
                                        </div>
                                    </div>
                                </div>
                            </template>
                    </div>
                </div>
                <a
                    v-if="message.chat_type === models.message.type_group"
                    class="flex cursor-pointer bg-white text-red-600 dark:bg-dark-300 my-2 px-6 py-4"
                >
                    <svg class="svg-icon !fill-red-600 !stroke-red-600" viewBox="0 0 20 20">
                        <path d="M8.416,3.943l1.12-1.12v9.031c0,0.257,0.208,0.464,0.464,0.464c0.256,0,0.464-0.207,0.464-0.464V2.823l1.12,1.12c0.182,0.182,0.476,0.182,0.656,0c0.182-0.181,0.182-0.475,0-0.656l-1.744-1.745c-0.018-0.081-0.048-0.16-0.112-0.224C10.279,1.214,10.137,1.177,10,1.194c-0.137-0.017-0.279,0.02-0.384,0.125C9.551,1.384,9.518,1.465,9.499,1.548L7.76,3.288c-0.182,0.181-0.182,0.475,0,0.656C7.941,4.125,8.234,4.125,8.416,3.943z M15.569,6.286h-2.32v0.928h2.32c0.512,0,0.928,0.416,0.928,0.928v8.817c0,0.513-0.416,0.929-0.928,0.929H4.432c-0.513,0-0.928-0.416-0.928-0.929V8.142c0-0.513,0.416-0.928,0.928-0.928h2.32V6.286h-2.32c-1.025,0-1.856,0.831-1.856,1.856v8.817c0,1.025,0.832,1.856,1.856,1.856h11.138c1.024,0,1.855-0.831,1.855-1.856V8.142C17.425,7.117,16.594,6.286,15.569,6.286z"></path>
                    </svg>
                    <span class="ml-6">{{
                        trans.leave_the_group
                    }}</span>
                </a>
                <a class="flex cursor-pointer bg-white text-red-600 dark:bg-dark-300 my-2 px-6 py-4">
                    <svg class="svg-icon !fill-red-600 !stroke-red-600" viewBox="0 0 20 20">
                        <path d="M17.114,3.923h-4.589V2.427c0-0.252-0.207-0.459-0.46-0.459H7.935c-0.252,0-0.459,0.207-0.459,0.459v1.496h-4.59c-0.252,0-0.459,0.205-0.459,0.459c0,0.252,0.207,0.459,0.459,0.459h1.51v12.732c0,0.252,0.207,0.459,0.459,0.459h10.29c0.254,0,0.459-0.207,0.459-0.459V4.841h1.511c0.252,0,0.459-0.207,0.459-0.459C17.573,4.127,17.366,3.923,17.114,3.923M8.394,2.886h3.214v0.918H8.394V2.886z M14.686,17.114H5.314V4.841h9.372V17.114z M12.525,7.306v7.344c0,0.252-0.207,0.459-0.46,0.459s-0.458-0.207-0.458-0.459V7.306c0-0.254,0.205-0.459,0.458-0.459S12.525,7.051,12.525,7.306M8.394,7.306v7.344c0,0.252-0.207,0.459-0.459,0.459s-0.459-0.207-0.459-0.459V7.306c0-0.254,0.207-0.459,0.459-0.459S8.394,7.051,8.394,7.306"></path>
                    </svg>
                    <span class="ml-6">{{
                        trans.delete_chat
                    }}</span>
                </a>
            </div>
        </aside>
    </section>
</template>

<script>
import debounce from 'lodash/debounce'

export default {
    data() {
        return {
            form: {
                content: ''
            },
            isChat: false,
            isDetail: false,
            isRight: false,
            left_detail: null,
            message: {},
            message_countdown: null,
            message_second: 0,
            message_typing: false,
            profile: {},
            userIndex: null,
            users: [],
            users_new_chat: [],
        }
    },
    
    methods: {

        fetchMessages(id, type)
        {
            if (this.form.to_id != id || this.form.group_id != id) {
                this.isRight = true

                if (type === this.models.message.type_user) {
                    this.form.to_id = id
                    this.form.chat_type = this.models.message.type_user
                    this.form.group_id = null
                }

                if (type === this.models.message.type_group) {
                    this.form.group_id = id
                    this.form.chat_type = this.models.message.type_group
                    this.form.to_id = null
                }
                
                axios.get(`message-show/${id}`, {
                    params: {
                        type
                    }
                }).then(({ data }) => {
                    this.message = data
    
                    this.users.find((s) => {
                        if (s.id === id && s.chat_type === this.models.message.type_user) {
                            s.read_count = 0
                            this.message.online = s.online
                        }
                    })

                    this.scrollToEnd()
                })

            }
        },

        fetchSearchUserChat: debounce( function(e) {

            this.fetchUsers(e.target.value)

        }, 500),

        fetchSearchNewChat: debounce(function (e) {
            axios
                .get('user-new-chat', {
                    params: {
                        q: e.target.value
                    }
                })
                .then(({ data }) => {
                    this.users_new_chat = data
                })
        }, 500),

        fetchUsers(q = '')
        {
            axios.get('user-chat', {
                params: { q }
            })
            .then(({ data }) => {
                this.users = data
            })
        },

        isTyping() {
            let _this = this

            if (this.message_second <= 0 && !this.message_typing) {

                this.message_typing = true
                this.sendTyping()

            }

            this.message_second = 3

            clearInterval(this.message_countdown)

            this.message_countdown = setInterval( function() {

                if (--_this.message_second <= 0 && _this.message_typing) {

                    clearInterval(_this.message_countdown)

                    _this.message_typing = false
                    _this.sendTyping()

                }

            }, 1000)
        },

        listenEcho()
        {
            Echo.channel('laratalk-user-message.' + this.laratalk.profile.id)
                .listen('Messages\\SendEvent', (e) => {

                    this.pushMessage(e)

                })
                .listen('Messages\\StatusEvent', (e) => {
                    this.users.find((s) => {
                        if (s.id === e.content_to) {
                            
                            s.status = e.status
    
                            if (this.message.messages) {
                                (
                                    typeof e.id === 'number'
                                        ? [e.id] : e.id
                                )
                                .forEach((id) => {
                                    this.message.messages
                                        .find((s) => {
                                            if (s.id === id) {

                                                s.status = e.status
                                                
                                            }
                                        })
                                })
                            }
                        }
                    })
                })
            
            Echo.join('online')
                .here((users) => {
                    users.forEach((e) => {
                        this.users.find((s) => {
                            if (
                                s.id === e.id &&
                                s.type_group === e.type_group
                            ) {
                                s.online = true
                            }
                        })
                    })
                })
                .joining((e) => {
                    this.users
                        .find((s) => {
                            if (
                                s.id === e.id &&
                                s.chat_type === e.chat_type
                            ) {
                                s.online = true
                            }
                        })
                    
                    if (
                        this.message.id === e.id &&
                        this.form.chat_type === e.type_user
                    ) {
                        this.message.online = true
                    }
                })
                .leaving((e) => {
                    this.users
                        .find((s) => {
                            if (
                                s.id === e.id &&
                                s.chat_type === e.chat_type
                            ) {
                                s.online = false
                            }
                        })

                    if (
                        this.message.id === e.id
                        && this.form.chat_type === e.type_user
                    ) {
                        this.message.online = false
                    }
                })
            
            Echo.private('chat')
                .listenForWhisper(`typing-${this.laratalk.profile.id}`, (e) => {

                    this.users
                        .find((s) => {
                            if (s.id === e.id) {
                                s.typing = e.typing
                            }
                        })
                    
                    if (this.message.id === e.id) {
                        this.message.typing = e.typing
                    }

                })

        },

        pushMessage(data)
        {
            let userIndex = -1

            if (data.chat_type === this.models.message.type_user) {
                userIndex = this.users.findIndex(
                    (s) =>  s.id === (
                        this.laratalk.profile.id == data.content_by
                            ? this.message.id : data.content_by )
                )
            }
            else {
                userIndex = this.users.findIndex(
                    (e => e.chat_type === this.models.message.type_group && e.id === data.group_id)
                )
            }

            if (userIndex != -1) {

                const user = this.users[userIndex]
                
                user.content = data.content
                user.content_by = data.content_by
                user.last_time = data.time
                user.status = 'send'

                if (
                    this.laratalk.profile.id != data.content_by &&
                    this.message.id != data.content_by
                ) {
                    user.read_count++
                }

                this.users.unshift(
                    this.users.splice(userIndex, 1)[0]
                )

            } else {
                this.users.unshift({
                    id: this.message.id || data.content_by,
                    avatar: this.message.avatar || data.avatar,
                    name: this.message.name || data.name,
                    content: data.content,
                    content_by: data.content_by,
                    content_type: 0,
                    chat_type: data.chat_type,
                    read_count:
                        this.laratalk.profile.id != data.content_by ? 1 : 0,
                    status: data.status,
                    last_time: data.time
                })
            }

            if (
                this.message.messages && (
                    this.laratalk.profile.id === data.content_by ||
                    this.message.id === data.content_by
                )
            ) {

                this.message.messages.push(data)
            
                this.scrollToEnd()

            }

            if (this.laratalk.profile.id != data.content_by) {

                let status = this.message.id == data.content_by
                    ? 'read' : 'accept'

                axios.post('message-status', {
                    id: data.id,
                    content_by: data.content_by,
                    status
                })
                
            }
        },

        resetLeft() {
            this.left_detail = null
            this.users_new_chat = []
        },

        scrollToEnd()
        {
            setTimeout(() => {
                let container = this.$el.querySelector("#main-content")
                container.scrollTop = container.scrollHeight
            }, 10)
        },

        sendMessage()
        {
            this.sendTyping(false)
            
            axios.post('message-store', this.form).then(({ data }) => {
                this.form.content = ''
                this.search = ''
                
                this.pushMessage(data)
            })
        },

        sendTyping(bool = null) {
            if (bool != null) {
                this.message_typing = bool
            }

            Echo.private('chat')
                .whisper(`typing-${this.message.id}`, {
                    id: this.laratalk.profile.id,
                    typing: bool || this.message_typing
                })
        }

    },

    beforeMount() {
        this.fetchUsers()
        this.listenEcho()
    }
}
</script>