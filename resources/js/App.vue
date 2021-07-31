<template>
    <section class="flex h-full dark:bg-dark-100 dark:text-gray-100">
        <!-- box aside profile -->
        <BoxAside
            v-model="isBoxProfile"
            :name="trans.profile"
        >
            <div class="sidebar-detail">
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
        </BoxAside>

        <!-- box aside new chat -->
        <BoxAside
            v-model="isBoxChat"
            :name="trans.new_chat"
        >
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
                        @click="fetchMessages(item.id, models.message.type_user);isBoxChat=false"
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
        </BoxAside>

        <!-- box aside new group -->
        <BoxAside
            v-model="isBoxNewGroup"
            :name="trans.select_group_participants"
        >
            <div class="flex flex-col bg-white dark:bg-dark-300" style="height: calc(100vh - 3.5rem)">
                <div
                    class="min-h-7 max-h-50 relative overflow-y-auto overflow-x-hidden border-b dark:border-dark-50 ml-10 mt-7 mb-4"
                    id="add-group-participant"
                >

                    <div
                        v-for="(item, index) in users_add_Groups"
                        :key="index"
                        class="mb-1"
                    >
                        <div class="inline-flex bg-light-300 dark:bg-dark-100 rounded-full">
                            <div class="flex-none">
                                <img
                                    class="rounded-full w-7 h-7"
                                    :src="item.avatar"
                                    alt="profile"
                                >
                            </div>
                            <div class="flex-grow my-auto mx-1">
                                <p class="text-md">{{
                                    item.name
                                }}</p>
                            </div>
                            <div class="flex-none my-auto p-1">
                                <a
                                    @click="removeGroupParticipant(index)"
                                    class="cursor-pointer"
                                >
                                    <svg class="svg-icon !w-4 !h-4" viewBox="0 0 20 20">
                                        <path d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <input
                        type="search"
                        class="flex-grow dark:bg-dark-300 focus:outline-none w-full" 
                        :placeholder="trans.search_participant_name"
                        @input="fetchSearchNewChat"
                        autofocus
                    >
                </div>

                <div class="h-full overflow-y-auto overflow-x-hidden relative bg-white dark:bg-dark-300 dark:divide-gray-700 divide-light-500">
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
                            @click="addGroupParticipant(item)"
                            v-if="users_add_Groups.findIndex((s) => s.id === item.id) == -1"
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
                <div class="flex justify-center items-center w-full min-h-18 bg-light-600 dark:bg-dark-200 h-20 mx-auto my-auto">
                    <a
                        v-if="users_add_Groups.length"
                        @click="isBoxGroup=true"
                        class="cursor-pointer rounded-full p-3 bg-violet-600 shadow-xl"
                    >
                        <svg class="svg-icon !fill-white !stroke-white" viewBox="0 0 20 20">
                            <path fill="none" d="M11.611,10.049l-4.76-4.873c-0.303-0.31-0.297-0.804,0.012-1.105c0.309-0.304,0.803-0.293,1.105,0.012l5.306,5.433c0.304,0.31,0.296,0.805-0.012,1.105L7.83,15.928c-0.152,0.148-0.35,0.223-0.547,0.223c-0.203,0-0.406-0.08-0.559-0.236c-0.303-0.309-0.295-0.803,0.012-1.104L11.611,10.049z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </BoxAside>

        <!-- box aside group -->
        <BoxAside
            v-model="isBoxGroup"
            :name="trans.new_group"
        >
            <div
                class="border-b dark:border-dark-50 ml-10"
            >
                <figure class="py-8">
                    <img
                        class="rounded-full h-48 w-48 mx-auto cursor-pointer"
                        :src="laratalk.profile.avatar"
                        alt="avatar"
                    >
                </figure>

                <input
                    v-model="formGroup.name"
                    type="search"
                    class="flex-grow dark:bg-dark-200 focus:outline-none w-full" 
                    :placeholder="trans.group_name"
                    autofocus
                >

            </div>
            <div
                v-if="formGroup.name.length"
                class="mx-auto my-8"
            >
                <a
                    @click="sendGroup"
                    class="cursor-pointer"
                >
                    <div class="bg-blue-700 rounded-full p-2">
                        <svg class="svg-icon !h-7 !w-7" viewBox="0 0 20 20">
                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg>
                    </div>
                </a>
            </div>
        </BoxAside>

        <!-- box aside settings -->
        <BoxAside
            v-model="isBoxSetting"
            :name="trans.settings"
        >
            <div
                @click="isBoxProfile=true"
                class="flex cursor-pointer hover:bg-light-300 dark:hover:bg-dark-100"
            >
                <div class="flex-none p-4">
                    <img
                        class="rounded-full h-20 w-20 cursor-pointer"
                        :src="laratalk.profile.avatar"
                        alt="profile"
                    >
                </div>
                <div class="flex-grow my-auto">
                    <p class="text-xl">{{
                        laratalk.profile.name
                    }}</p>
                </div>
            </div>
            <div
                @click="isSettingTheme=true"
                class="flex cursor-pointer hover:bg-light-300 dark:hover:bg-dark-100"
            >
                <div class="flex-none py-4 px-6">
                    <svg class="svg-icon" viewBox="0 0 20 20">
                        <path d="M10,0.625c-5.178,0-9.375,4.197-9.375,9.375S4.822,19.375,10,19.375s9.375-4.197,9.375-9.375S15.178,0.625,10,0.625 M10,18.522V1.478c4.707,0,8.522,3.815,8.522,8.522S14.707,18.522,10,18.522"></path>
                    </svg>
                </div>
                <div class="flex-grow my-auto">
                    <p class="text-md">{{
                        trans.theme
                    }}</p>
                </div>
            </div>
        </BoxAside>

        <aside
            :class="[`flex-none min-w-100 sm:border-r-1 dark:sm:border-dark-200 <sm:w-full <lg:fixed <lg:top-0 <lg:left-0 <lg:z-20 ease-in-out transition-all duration-300`, {
                '<sm:-left-full <lg:-left-100': !isContactChat
            }]"
        >
            <div class="flex bg-light-600 dark:bg-dark-400 px-5 py-2">
                <div class="flex-grow">
                    <img
                        @click="isBoxProfile=true"
                        class="rounded-full h-10 w-10 cursor-pointer"
                        :src="laratalk.profile.avatar"
                        alt="profile"
                    >
                </div>
                <div class="flex flex-grow-0 flex-shrink-0 my-auto">
                    <a class="cursor-pointer mx-2" @click="isBoxChat=true">
                        <svg class="svg-icon" viewBox="0 0 20 20">
							<path d="M17.211,3.39H2.788c-0.22,0-0.4,0.18-0.4,0.4v9.614c0,0.221,0.181,0.402,0.4,0.402h3.206v2.402c0,0.363,0.429,0.533,0.683,0.285l2.72-2.688h7.814c0.221,0,0.401-0.182,0.401-0.402V3.79C17.612,3.569,17.432,3.39,17.211,3.39M16.811,13.004H9.232c-0.106,0-0.206,0.043-0.282,0.117L6.795,15.25v-1.846c0-0.219-0.18-0.4-0.401-0.4H3.189V4.19h13.622V13.004z"></path>
						</svg>
                    </a>
                    <div class="dropdown mx-2">
                        <a class="dropdown-button">
                            <svg class="svg-icon transform rotate-90" viewBox="0 0 20 20">
                                <path d="M3.936,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021S5.957,11.116,5.957,10 S5.052,7.979,3.936,7.979z M3.936,11.011c-0.558,0-1.011-0.452-1.011-1.011s0.453-1.011,1.011-1.011S4.946,9.441,4.946,10 S4.494,11.011,3.936,11.011z M16.064,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021s2.021-0.905,2.021-2.021 S17.181,7.979,16.064,7.979z M16.064,11.011c-0.559,0-1.011-0.452-1.011-1.011s0.452-1.011,1.011-1.011S17.075,9.441,17.075,10 S16.623,11.011,16.064,11.011z M10,7.979c-1.116,0-2.021,0.905-2.021,2.021S8.884,12.021,10,12.021s2.021-0.905,2.021-2.021 S11.116,7.979,10,7.979z M10,11.011c-0.558,0-1.011-0.452-1.011-1.011S9.442,8.989,10,8.989S11.011,9.441,11.011,10 S10.558,11.011,10,11.011z"></path>
                            </svg>
                        </a>
                        <div class="dropdown-menu">
                            <a
                                @click="isBoxNewGroup=true"
                                class="dropdown-item"
                            >{{
                                trans.new_group
                            }}</a>
                            <a
                                @click="isBoxProfile=true"
                                class="dropdown-item"
                            >{{
                                trans.profile
                            }}</a>
                            <a
                                @click="isBoxSetting=true"
                                class="dropdown-item"
                            >{{
                                trans.settings
                            }}</a>
                            <a
                                href="/"
                                class="dropdown-item"
                            >{{
                                trans.exit
                            }}</a>
                        </div>
                    </div>
                    <a v-if="message.messages" class="cursor-pointer mx-2 -mr-2 lg:hidden" @click="isContactChat = !isContactChat">
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
                    :class="[`sidebar-list flex h-18 cursor-pointer`, {
                        'font-medium': item.read_count,
                        'hover:bg-light-300 dark:hover:bg-true-gray-700':
                            form.type_id != getTypeId(item.chat_type, item.id),
                        'bg-light-500 dark:bg-true-gray-800':
                            form.type_id == getTypeId(item.chat_type, item.id)
                    }]"
                >
                    <div
                        @click="fetchMessages(item.id, item.chat_type)"
                        class="flex flex-col justify-center px-3"
                    >
                        <img
                            class="rounded-full h-13 w-13"
                            :src="item.avatar"
                            alt="avatar"
                        >
                    </div>
                    <div class="flex flex-grow flex-col justify-center w-73 pr-3">
                        <div
                            @click="fetchMessages(item.id, item.chat_type)"
                            class="flex"
                        >
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
                            <span
                                v-if="item.typing"
                                @click="fetchMessages(item.id, item.chat_type)"
                                class="flex-grow"
                            >{{
                                item.typing_name
                                    ? `${item.typing_name}: ${trans.typing}`
                                    : trans.typing
                            }}</span>
                            <template v-else>
                                <div
                                    @click="fetchMessages(item.id, item.chat_type)"
                                    class="contents flex-grow"
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
                                        item.content_type === models.message.chat
                                            ? item.content : getTransMessage(item)
                                    }}</span>
                                </div>
                            </template>
                            <small
                                @click="fetchMessages(item.id, item.chat_type)"
                                v-if="item.read_count"
                                class="flex-none bg-purple-600 text-white w-5 h-5 leading-5 text-center rounded-full"
                            >{{
                                item.read_count
                            }}</small>
                            <div class="dropdown -mr-9 pl-3 transition-effect opacity-0">
                                <a class="dropdown-button">
                                    <svg class="svg-icon transform -rotate-90" viewBox="0 0 20 20">
                                        <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                    </svg>
                                </a>
                                <div class="dropdown-menu">
                                    <a
                                        @click="deleteChatorLeaveGroup(item.id, item.chat_type)"
                                        class="dropdown-item"
                                    >{{
                                        item.chat_type === models.message.type_user
                                            ? trans.delete_chat : trans.leave_the_group
                                    }}</a>
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

                <!-- profile user or group -->
                <div class="bg-light-600 dark:bg-dark-400 px-2">
                    <div class="flex">
                        <div class="flex-none p-2 my-auto lg:hidden">
                            <a class="cursor-pointer" @click="isContactChat = !isContactChat">
                                <svg class="svg-icon" viewBox="0 0 20 20">
                                    <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="flex-none p-2 cursor-pointer" @click="isDetailUser = true">
                            <img
                                class="rounded-full h-10 w-10"
                                :src="message.avatar"
                                alt="avatar"
                            >
                        </div>
                        <div class="flex-grow grid cursor-pointer my-auto" @click="isDetailUser = true">
                            <p class="text-base">{{
                                message.name
                            }}</p>
                            <small v-if="message.typing" class="text-sm leading-none">{{
                                message.typing_name
                                    ? `${message.typing_name}: ${trans.typing}`
                                    : trans.typing
                            }}</small>
                            <small 
                                v-if="!message.typing && message.chat_type === models.message.type_group"
                                class="text-sm leading-none truncate"
                                :title="getUserGroup(message.users)"
                            >{{
                                getUserGroup(message.users)
                            }}</small>
                            <small
                                v-if="message.online && !message.typing && message.chat_type === models.message.type_user"
                                class="text-sm leading-none"
                            >{{
                                trans.online
                            }}</small>
                        </div>
                        <div class="flex flex-none p-2 my-auto">
                            <div class="dropdown">
                                <a class="dropdown-button">
                                    <svg class="svg-icon transform rotate-90" viewBox="0 0 20 20">
                                        <path d="M3.936,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021S5.957,11.116,5.957,10 S5.052,7.979,3.936,7.979z M3.936,11.011c-0.558,0-1.011-0.452-1.011-1.011s0.453-1.011,1.011-1.011S4.946,9.441,4.946,10 S4.494,11.011,3.936,11.011z M16.064,7.979c-1.116,0-2.021,0.905-2.021,2.021s0.905,2.021,2.021,2.021s2.021-0.905,2.021-2.021 S17.181,7.979,16.064,7.979z M16.064,11.011c-0.559,0-1.011-0.452-1.011-1.011s0.452-1.011,1.011-1.011S17.075,9.441,17.075,10 S16.623,11.011,16.064,11.011z M10,7.979c-1.116,0-2.021,0.905-2.021,2.021S8.884,12.021,10,12.021s2.021-0.905,2.021-2.021 S11.116,7.979,10,7.979z M10,11.011c-0.558,0-1.011-0.452-1.011-1.011S9.442,8.989,10,8.989S11.011,9.441,11.011,10 S10.558,11.011,10,11.011z"></path>
                                    </svg>
                                </a>
                                <div class="dropdown-menu">
                                    <a
                                         @click="isDetailUser = true"
                                        class="dropdown-item"
                                    >{{
                                        message.chat_type === models.message.type_user
                                            ? trans.contact_info : trans.group_info
                                    }}</a>
                                    <a
                                        v-if="message.chat_type === models.message.type_user"
                                        @click="deleteChatorLeaveGroup(message.id, message.chat_type)"
                                        class="dropdown-item"
                                    >{{
                                        trans.delete_chat
                                    }}</a>
                                    <a
                                        @click="deleteChatorLeaveGroup(message.id, message.chat_type)"
                                        v-if="message.chat_type === models.message.type_group"
                                        class="dropdown-item"
                                    >{{
                                        trans.leave_the_group
                                    }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- all messages -->
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
                            v-if="
                                !includes(
                                    [models.message.chat, models.message.pull_message],
                                    item.content_type
                                ) &&
                                message.chat_type === models.message.type_group
                            "
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
                                <div
                                    v-if="item.content_type === models.message.chat"
                                    class="dropdown z-20"
                                >
                                    <a class="dropdown-button absolute w-10 bg-gradient-to-tr from-dark-500/80 to-dark-500 right-0 opacity-0 transition-effect">
                                        <svg class="svg-icon !bg-dark-500 float-right transform -rotate-90" viewBox="0 0 20 20">
                                            <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu !mt-6 !w-36">
                                        <a
                                            @click="sendPullMessage(item.id, message)"
                                            class="dropdown-item"
                                        >{{
                                            trans.pull_message
                                        }}</a>
                                    </div>
                                </div>
                                <p>
                                    <span
                                        :class="[`whitespace-pre-wrap`, {
                                            'italic': item.content_type !== models.message.chat
                                        }]"
                                    >{{
                                        item.content_type === models.message.chat
                                            ? item.content
                                            : getTransMessage(item)
                                    }}</span>
                                    <span class="w-18 inline-block"></span>
                                </p>
                                <small class="flex float-right -mt-3 -mb-5px z-30">{{
                                    item.time
                                }}
                                    <template v-if="item.content_type === models.message.chat">
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
                                    </template>
                                </small>
                            </div>
                            
                            <!-- message left -->
                            <div
                                v-else
                                class="message bg-white dark:text-dark-300 rounded-r-xl rounded-t-xl shadow-lg shadow-dark-100 dark:shadow-light-100 max-w-66/100 my-4 p-2 mr-auto"
                            >
                                <div
                                    v-if="item.content_type === models.message.chat"
                                    class="dropdown z-20"
                                >
                                    <a class="dropdown-button absolute w-10 bg-gradient-to-tr from-white/80 to-white right-0 opacity-0 transition-effect">
                                        <svg class="svg-icon !bg-white !fill-gray-400 !stroke-gray-400 float-right transform -rotate-90" viewBox="0 0 20 20">
                                            <path d="M8.388,10.049l4.76-4.873c0.303-0.31,0.297-0.804-0.012-1.105c-0.309-0.304-0.803-0.293-1.105,0.012L6.726,9.516c-0.303,0.31-0.296,0.805,0.012,1.105l5.433,5.307c0.152,0.148,0.35,0.223,0.547,0.223c0.203,0,0.406-0.08,0.559-0.236c0.303-0.309,0.295-0.803-0.012-1.104L8.388,10.049z"></path>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu !mt-6 !w-36">
                                        <!-- <a class="dropdown-item"></a> -->
                                    </div>
                                </div>
                                <a
                                    @click="fetchMessages(item.content_by, models.message.type_user)"
                                    v-if="message.chat_type === models.message.type_group"
                                    class="cursor-pointer !text-cyan-500 hover:underline"
                                >{{
                                    item.user_by_name
                                }}</a>
                                <p>
                                    <span
                                        :class="[`whitespace-pre-wrap`, {
                                            'italic': item.content_type !== models.message.chat
                                        }]"
                                    >{{
                                        item.content_type === models.message.chat
                                            ? item.content
                                            : getTransMessage(item)
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

                <!-- form chat -->
                <div class="flex bg-light-600 dark:bg-dark-500">
                    <div class="flex-grow pl-4 pr-2 py-2">
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
                    <div class="flex-none my-auto pl-2 pr-4">
                        <a
                            @click="sendMessage"
                            class="cursor-pointer"
                        >
                            <svg class="svg-icon" viewBox="0 0 20 20">
                                <path d="M17.218,2.268L2.477,8.388C2.13,8.535,2.164,9.05,2.542,9.134L9.33,10.67l1.535,6.787c0.083,0.377,0.602,0.415,0.745,0.065l6.123-14.74C17.866,2.46,17.539,2.134,17.218,2.268 M3.92,8.641l11.772-4.89L9.535,9.909L3.92,8.641z M11.358,16.078l-1.268-5.613l6.157-6.157L11.358,16.078z"></path>
                            </svg>
                        </a>
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

        <!-- detail info contact or group -->
        <aside :class="[`flex-none bg-light-600 dark:bg-dark-100 w-0 <xl:fixed <xl:top-0 <xl:right-0 z-10 ease-in-out transition-all duration-300`, {
            'w-100 <sm:w-full md:border-l-1 dark:md:border-dark-200': isDetailUser
        }]">
            <div class="flex bg-blue-500 dark:bg-dark-400 text-white p-4">
                <a class="cursor-pointer" @click="isDetailUser = !isDetailUser">
                    <svg class="svg-icon !fill-white !stroke-white" viewBox="0 0 20 20">
                        <path d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                    </svg>
                </a>
                <p class="text-base ml-3">{{
                    message.chat_type === models.message.type_user
                        ? trans.contact_info : trans.group_info
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
                    @click="deleteChatorLeaveGroup(message.id, message.chat_type)"
                    class="flex cursor-pointer bg-white text-red-600 dark:bg-dark-300 my-2 px-6 py-4"
                >
                    <svg class="svg-icon !fill-red-600 !stroke-red-600" viewBox="0 0 20 20">
                        <path v-if="message.chat_type === models.message.type_group" d="M8.416,3.943l1.12-1.12v9.031c0,0.257,0.208,0.464,0.464,0.464c0.256,0,0.464-0.207,0.464-0.464V2.823l1.12,1.12c0.182,0.182,0.476,0.182,0.656,0c0.182-0.181,0.182-0.475,0-0.656l-1.744-1.745c-0.018-0.081-0.048-0.16-0.112-0.224C10.279,1.214,10.137,1.177,10,1.194c-0.137-0.017-0.279,0.02-0.384,0.125C9.551,1.384,9.518,1.465,9.499,1.548L7.76,3.288c-0.182,0.181-0.182,0.475,0,0.656C7.941,4.125,8.234,4.125,8.416,3.943z M15.569,6.286h-2.32v0.928h2.32c0.512,0,0.928,0.416,0.928,0.928v8.817c0,0.513-0.416,0.929-0.928,0.929H4.432c-0.513,0-0.928-0.416-0.928-0.929V8.142c0-0.513,0.416-0.928,0.928-0.928h2.32V6.286h-2.32c-1.025,0-1.856,0.831-1.856,1.856v8.817c0,1.025,0.832,1.856,1.856,1.856h11.138c1.024,0,1.855-0.831,1.855-1.856V8.142C17.425,7.117,16.594,6.286,15.569,6.286z"></path>
                        <path v-else d="M17.114,3.923h-4.589V2.427c0-0.252-0.207-0.459-0.46-0.459H7.935c-0.252,0-0.459,0.207-0.459,0.459v1.496h-4.59c-0.252,0-0.459,0.205-0.459,0.459c0,0.252,0.207,0.459,0.459,0.459h1.51v12.732c0,0.252,0.207,0.459,0.459,0.459h10.29c0.254,0,0.459-0.207,0.459-0.459V4.841h1.511c0.252,0,0.459-0.207,0.459-0.459C17.573,4.127,17.366,3.923,17.114,3.923M8.394,2.886h3.214v0.918H8.394V2.886z M14.686,17.114H5.314V4.841h9.372V17.114z M12.525,7.306v7.344c0,0.252-0.207,0.459-0.46,0.459s-0.458-0.207-0.458-0.459V7.306c0-0.254,0.205-0.459,0.458-0.459S12.525,7.051,12.525,7.306M8.394,7.306v7.344c0,0.252-0.207,0.459-0.459,0.459s-0.459-0.207-0.459-0.459V7.306c0-0.254,0.207-0.459,0.459-0.459S8.394,7.051,8.394,7.306"></path>
                    </svg>
                    <span class="ml-6">{{
                        message.chat_type === models.message.type_user
                            ? trans.delete_chat : trans.leave_the_group
                    }}</span>
                </a>
            </div>
        </aside> <!-- end detail info contact or group -->
    </section>

    <!-- modal setting theme -->
    <modal
        v-model="isSettingTheme"
        :onCancel="false"
    >
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-light-700">{{
            trans.theme
        }}</h3>
        <div class="block mt-5 dark:text-light-700">
            <div>
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        v-model="dark_mode"
                        type="radio"
                        class="form-radio cursor-pointer"
                        :value="false"
                        v-bind:checked="!dark_mode"
                    >
                    <span class="ml-2">{{
                        trans.light
                    }}</span>
                </label>
            </div>
            <div>
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        v-model="dark_mode"
                        type="radio"
                        class="form-radio cursor-pointer"
                        :value="true"
                        v-bind:checked="dark_mode"
                    >
                    <span class="ml-2">{{
                        trans.dark
                    }}</span>
                </label>
            </div>
        </div>
    </modal>
</template>

<script>
import debounce from 'lodash/debounce'
import includes from 'lodash/includes'

export default {
    data() {
        return {
            form: {
                content: ''
            },
            formGroup: {
                name: '',
                users: []
            },
            isBoxChat: false,
            isBoxGroup: false,
            isBoxNewGroup: false,
            isBoxProfile: false,
            isBoxSetting: false,
            isContactChat: true,
            isDetailUser: false,
            isSettingTheme: false,
            message: {},
            message_countdown: null,
            message_second: 0,
            message_typing: false,
            profile: {},
            userIndex: null,
            users: [],
            users_add_Groups: [],
            users_new_chat: []
        }
    },
    methods: {
        // lodash includes
        includes,

        addGroupParticipant(user)
        {
            this.users_add_Groups.push(user)

            setTimeout(() => {
                let container = document.getElementById("add-group-participant")
                container.scrollTop = container.scrollHeight
            }, 1)
        },

        deleteChatorLeaveGroup(id, chat_type)
        {
            const text = chat_type === this.models.message.type_user
                ? this.trans.delete_this_chat
                : this.trans.leave_this_group

            this.$modal({
                content: text,
                onConfirm: () => {
                    axios
                        .post('message-destroy', {
                            id,
                            chat_type
                        })
                        .then(() => {
                            this.isContactChat = true
                            this.isDetailUser = false

                            const index = this.users.findIndex(
                                    (e) =>
                                        e.chat_type === chat_type &&
                                        e.id === id
                                )
                            
                            this.users.splice(index, 1)

                            if (
                                this.message.id === id &&
                                this.message.chat_type === chat_type
                            ) {

                                this.message = {}
                                
                            }
                        })
                }
            })
        },

        fetchMessages(id, type)
        {
            this.isContactChat = false

            const type_id = this.getTypeId(type, id)

            if (this.form.type_id !== type_id) {

                this.form.type_id = type_id
                this.form.content = ''

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

                        if (this.getTypeId(s.chat_type, s.id) === type_id) {
                            s.read_count = 0

                            if (s.chat_type === this.models.message.type_user) {
                                this.message.online = s.online
                            }
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

                data.forEach((s) => {
                    if (s.chat_type === this.models.message.type_group) {
                        this.listenTyping(
                            this.getTypeId(s.chat_type, s.id)
                        )
                    }
                })
            })
        },

        getUserGroup(data)
        {
            let string = ''

            data.forEach(s => {
                string += this.firstName(s.name) + ', '
            })

            return string.substr(0, string.length - 2)
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

                        if (
                            s.id === e.content_to &&
                            s.chat_type === e.chat_type
                        ) {
                            
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
                .listen('Messages\\PullMessageEvent', (e) => {

                    this.pullMessage(e)

                })
                .listen('Groups\\NewGroupEvent', (e) => {
                    this.users.unshift(e)

                    this.listenTyping(
                        this.getTypeId(e.chat_type, e.id)
                    )
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
            
            this.listenTyping(`user-` + this.laratalk.profile.id)

        },

        listenTyping(target)
        {
            Echo.private('chat')
                .listenForWhisper(`typing-${target}`, (e) => {

                    this.users
                        .find((s) => {
                            if (
                                s.id === e.id &&
                                s.chat_type === e.chat_type
                            ) {
                                s.typing = e.typing
                                s.typing_name = e.typing_name || ''
                            }
                        })
                    
                    if (
                        this.message.id === e.id &&
                        this.message.chat_type === e.chat_type
                    ) {
                        this.message.typing = e.typing
                        this.message.typing_name = e.typing_name || ''
                    }

                })
        },

        pullMessage(data)
        {
            if (
                this.message &&
                this.message.id === data.target_user_id &&
                this.message.chat_type === data.chat_type
            ) {
                this.message.messages.filter((message) => {
                    if (message.id === data.id) {
                        message.content = ''
                        message.content_type = data.content_type
                    }
                })
            }
            
            this.users.filter((user) => {
                if (
                    user.id === data.target_user_id &&
                    user.chat_type === data.chat_type
                ) {
                    user.content = ''
                    user.content_type = data.content_type
                }
            })
        },

        pushMessage(data)
        {
            let userIndex = -1

            if (data.chat_type === this.models.message.type_user) {
                userIndex = this.users.findIndex(
                    (e) => e.chat_type === this.models.message.type_user &&
                        e.id === (
                            this.laratalk.profile.id == data.content_by
                                ? this.message.id : data.content_by
                        )
                )
            }
            else {
                userIndex = this.users.findIndex(
                    (e) => 
                        e.chat_type === this.models.message.type_group &&
                        e.id === data.group_id
                )
            }
            
            if (userIndex != -1) {

                const user = this.users[userIndex]
                
                user.content = data.content
                user.content_by = data.content_by
                user.content_type = data.content_type
                user.user_by_name = data.user_by_name || ''
                user.last_time = data.time
                user.status = 'send'

                if (
                    this.getTypeId(user.chat_type, user.id) !==
                        this.getTypeId(this.message.chat_type, this.message.id)
                ) {
                    user.read_count++
                }

                this.users.unshift(
                    this.users.splice(userIndex, 1)[0]
                )

            } else {

                this.users.unshift({
                    id: data.avatar ? data.content_by : this.message.id,
                    avatar: data.avatar ? data.avatar : this.message.avatar,
                    name: data.name ? data.name : this.message.name,
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
                this.message.messages &&
                this.getTypeId(data.chat_type, this.message.id) ===
                    this.getTypeId(this.message.chat_type, this.message.id)
            ) {

                this.message.messages.push(data)
            
                this.scrollToEnd()

            }

            if (this.laratalk.profile.id != data.content_by) {

                let status = (
                    this.message.chat_type === this.models.message.type_user &&
                    this.message.id == data.content_by
                ) ? 'read' : 'accept'

                axios.post('message-status', {
                    id: data.id,
                    content_by: data.content_by,
                    status
                })
                
            }
        },

        sendPullMessage(id, user)
        {
            axios
                .post('message-pull', {
                    id,
                    user_id: user.id,
                    chat_type: user.chat_type
                })
                .then(({ data }) => {

                    this.pullMessage(data)

                })
        },

        removeGroupParticipant(index)
        {
            this.users_add_Groups.splice(index, 1)
        },

        resetLeft() {
            setTimeout(() => {
                this.users_new_chat = []
                this.users_add_Groups = []
            }, 500)
        },

        scrollToEnd()
        {
            setTimeout(() => {
                let container = document.getElementById('main-content')
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

        sendGroup()
        {
            this.users_add_Groups.filter(e => {
                this.formGroup.users.push(e.id)
            })

            axios.post('group-create', this.formGroup)
                .then(() => {
                    this.isBoxNewGroup = false
                    this.isBoxGroup = false
                    this.formGroup = {
                        name: '',
                        users: []
                    }
                    this.users_add_Groups = []
                })
        },

        sendTyping(bool = null) {
            if (bool != null) {
                this.message_typing = bool
            }

            const data = {
                id: this.message.chat_type === this.models.message.type_group
                    ? this.message.id : this.laratalk.profile.id,
                typing: bool || this.message_typing,
                chat_type: this.message.chat_type
            }

            if (this.message.chat_type === this.models.message.type_group) {
                data.typing_name = this.firstName(
                    this.laratalk.profile.name
                )
            }

            Echo.private('chat')
                .whisper(`typing-${this.form.type_id}`, data)
        }

    },

    beforeMount() {
        this.fetchUsers()
        this.listenEcho()
    }
}
</script>