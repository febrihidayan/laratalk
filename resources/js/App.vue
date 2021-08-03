<template>
    <section class="flex h-full dark:bg-dark-100 dark:text-gray-100">
        <!-- box aside profile -->
        <BoxAside
            v-model="isBoxProfile"
            :name="trans.profile"
        >
            <div class="sidebar-detail">
                <div class="dark:bg-dark-300 py-8">
                    <Avatar
                        :image="laratalk.profile.avatar"
                        :isUpload="true"
                        :size="48"
                        class="flex justify-center"
                    />
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
                        <SearchIcon
                            class="svg-icon"
                        />
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
                    <Media
                        v-if="index == 0 || (users_new_chat[index-1] && users_new_chat[index-1].name.substr(0,1) != item.name.substr(0,1))"
                        class="mx-8 text-violet-600 dark:text-light-200"
                        :cursor="false"
                    >{{
                        item.name.substr(0,1)
                    }}</Media>

                    <Media
                        @click="fetchMessages(item.id, models.message.type_user);isBoxChat=false"
                    >
                        <template #left>
                            <Avatar
                                :image="item.avatar"
                                :size="13"
                            />
                        </template>

                        <div class="flex">
                            <p class="flex-grow truncate text-lg">{{
                                item.name
                            }}</p>
                        </div>
                    </Media>
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
                                <Avatar
                                    :image="item.avatar"
                                    :size="7"
                                />
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
                                    <XIcon
                                        class="svg-icon !w-4 !h-4"
                                    />
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
                        <Media
                            v-if="index == 0 || (users_new_chat[index-1] && users_new_chat[index-1].name.substr(0,1) != item.name.substr(0,1))"
                            class="mx-8 text-violet-600 dark:text-light-200"
                            :cursor="false"
                        >{{
                            item.name.substr(0,1)
                        }}</Media>

                        <Media
                            @click="addGroupParticipant(item)"
                            v-if="users_add_Groups.findIndex((s) => s.id === item.id) == -1"
                        >
                            <template #left>
                                <Avatar
                                    :image="item.avatar"
                                    :size="13"
                                />
                            </template>

                            <div class="flex">
                                <p class="flex-grow truncate text-lg">{{
                                    item.name
                                }}</p>
                            </div>
                        </Media>
                    </template>
                </div>
                <div class="flex justify-center items-center w-full min-h-18 bg-light-600 dark:bg-dark-200 h-20 mx-auto my-auto">
                    <a
                        v-if="users_add_Groups.length"
                        @click="isBoxGroup=true"
                        class="cursor-pointer rounded-full p-3 bg-violet-600 shadow-xl"
                    >
                        <ChevronRightIcon
                            class="svg-icon !text-white"
                        />
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
                <Avatar
                    :isIconGroup="true"
                    :isUpload="true"
                    :size="48"
                    class="py-8 flex justify-center"
                />

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
                    <div class="bg-violet-600 rounded-full p-2">
                        <CheckIcon
                            class="svg-icon !text-white !h-7 !w-7"
                        />
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
                    <Avatar
                        :image="laratalk.profile.avatar"
                        :size="20"
                    />
                </div>
                <div class="flex-grow my-auto">
                    <p class="text-xl">{{
                        laratalk.profile.name
                    }}</p>
                </div>
            </div>
            <Media
                @click="isSettingTheme=true"
                class="!h-15"
            >
                <template #left>
                    <div class="py-4 px-3">
                        <MoonIcon
                            v-if="dark_mode"
                            class="svg-icon"
                        />
                        <SunIcon
                            v-else
                            class="svg-icon"
                        />
                    </div>
                </template>

                <p class="text-md">{{
                    trans.theme
                }}</p>

            </Media>
            <Media
                @click="isSettingLang=true"
                class="!h-15"
            >
                <template #left>
                    <div class="py-4 px-3">
                        <TranslateIcon
                            class="svg-icon"
                        />
                    </div>
                </template>

                <p class="text-md">{{
                    trans.language
                }}</p>

            </Media>
        </BoxAside>

        <aside
            :class="[`flex-none min-w-100 sm:border-r-1 dark:sm:border-dark-200 <sm:w-full <lg:fixed <lg:top-0 <lg:left-0 <lg:z-20 ease-in-out transition-all duration-300`, {
                '<sm:-left-full <lg:-left-100': !isContactChat
            }]"
        >
            <div class="flex bg-light-600 dark:bg-dark-400 px-5 py-2">
                <div class="flex-grow">
                    <Avatar
                        @click="isBoxProfile=true"
                        :image="laratalk.profile.avatar"
                        class="cursor-pointer"
                    />
                </div>
                <div class="flex flex-grow-0 flex-shrink-0 my-auto">
                    <a class="cursor-pointer mx-2" @click="isBoxChat=true">
                        <AnnotationIcon
                            class="svg-icon"
                        />
                    </a>
                    <div class="dropdown mx-2">
                        <a class="dropdown-button">
                            <DotsVerticalIcon
                                class="svg-icon"
                            />
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
                        <XIcon
                            class="svg-icon"
                        />
                    </a>
                </div>
            </div>

            <!-- column search -->
            <div class="bg-light-200 dark:bg-dark-200 border-b-1 dark:border-dark-50 px-4 py-2">
                <div class="flex rounded-full bg-white dark:bg-dark-50 p-1">
                    <span class="flex-grow-0 flex-shrink-0">
                        <SearchIcon
                            class="svg-icon"
                        />
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
                <Media
                    v-for="(item, index) in users"
                    :key="index"
                    :isActive="form.type_id == getTypeId(item.chat_type, item.id)"
                    :class="[{
                        'font-medium': item.read_count
                    }]"
                >
                    <template #left>
                        <Avatar
                            @click="fetchMessages(item.id, item.chat_type)"
                            :image="item.avatar"
                            :size="13"
                        />
                    </template>

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
                                    <CheckIcon
                                        :class="[`svg-icon svg-sm flex-none`, {
                                            '!text-dark-200': !dark_mode && item.status != 'read',
                                            '!text-light-200': dark_mode && item.status != 'read',
                                            '!text-purple-400': item.status == 'read'
                                        }]"
                                    />
                                    <CheckIcon
                                        v-if="item.status != models.message.send"
                                        :class="[`svg-icon svg-sm flex-none -ml-4`, {
                                            '!text-dark-200': !dark_mode && item.status == 'accept',
                                            '!text-light-200': dark_mode && item.status == 'accept',
                                            '!text-purple-400': item.status == 'read'
                                        }]"
                                    />
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
                                <ChevronDownIcon
                                    class="svg-icon"
                                />
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
                </Media>
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
                                <ChevronLeftIcon
                                    class="svg-icon"
                                />
                            </a>
                        </div>
                        <div class="flex-none p-2 cursor-pointer" @click="isDetailUser = true">
                            <Avatar
                                :image="message.avatar"
                            />
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
                                    <DotsVerticalIcon
                                        class="svg-icon"
                                    />
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
                                        <ChevronDownIcon
                                            class="svg-icon !text-white float-right transform"
                                        />
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
                                        <CheckIcon
                                            :class="[`svg-icon svg-sm`, {
                                                '!text-light-200': item.status != 'read',
                                                '!text-purple-400': item.status == 'read'
                                            }]"
                                        />
                                        <CheckIcon
                                            v-if="item.status != 'send'"
                                            :class="[`svg-icon svg-sm -ml-4`, {
                                                '!text-light-200': item.status == 'accept',
                                                '!text-purple-400': item.status == 'read'
                                            }]"
                                        />
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
                                        <ChevronDownIcon
                                            class="svg-icon !bg-white !text-gray-400 float-right transform"
                                        />
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.218,2.268L2.477,8.388C2.13,8.535,2.164,9.05,2.542,9.134L9.33,10.67l1.535,6.787c0.083,0.377,0.602,0.415,0.745,0.065l6.123-14.74C17.866,2.46,17.539,2.134,17.218,2.268 M3.92,8.641l11.772-4.89L9.535,9.909L3.92,8.641z M11.358,16.078l-1.268-5.613l6.157-6.157L11.358,16.078z"></path>
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
                    <XIcon
                        class="svg-icon !text-white"
                    />
                </a>
                <p class="text-base ml-3">{{
                    message.chat_type === models.message.type_user
                        ? trans.contact_info : trans.group_info
                }}</p>
            </div>
            <div class="sidebar-detail">
                <div class="bg-white dark:bg-dark-300 p-6">
                    <Avatar
                        :image="message.avatar"
                        :isIconGroup="message.chat_type === models.message.type_group"
                        :size="48"
                        class="my-4 flex justify-center"
                    />
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
                            <Media
                                v-on:click="laratalk.profile.id != item.id ? fetchMessages(item.id, models.message.type_user) : ''"
                                :cursor="laratalk.profile.id != item.id"
                            >
                                <template #left>
                                    <Avatar
                                        :image="laratalk.profile.avatar"
                                        :size="13"
                                        class="pl-6"
                                    />
                                </template>

                                <div class="flex pr-6">
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
                            </Media>
                        </template>
                    </div>
                </div>
                <a
                    @click="deleteChatorLeaveGroup(message.id, message.chat_type)"
                    class="flex cursor-pointer bg-white text-red-600 dark:bg-dark-300 my-2 px-6 py-4"
                >
                    <LogoutIcon
                        v-if="message.chat_type === models.message.type_group"
                        class="svg-icon !text-red-600"
                    />
                    <TrashIcon
                        v-else
                        class="svg-icon !text-red-600"
                    />
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

    <!-- modal setting language -->
    <modal
        v-model="isSettingLang"
        :onCancel="false"
    >
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-light-700">{{
            trans.change_language
        }}</h3>
        <div class="block mt-5 dark:text-light-700">
            <div
                v-for="(lang, key) in laratalk.languages"
                :key="key"
            >
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        v-model="locale"
                        type="radio"
                        class="form-radio cursor-pointer"
                        :value="key"
                        @change="sendLanguage"
                    >
                    <span class="ml-2">{{
                        lang
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
            isSettingLang: false,
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

                    if (user.read_count) {
                        user.read_count--
                    }
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

        sendLanguage({ target})
        {
            axios
                .post('user-language', {
                    locale: target.value
                })
                .then(({ data }) => {
                    this.trans = data
                })
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