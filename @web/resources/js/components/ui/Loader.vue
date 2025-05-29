<template>
    <div class="loaderContainer">
        <div class="progressbar">
            <span class="loading-inner"></span>
        </div>
    </div>
</template>

<style lang="scss">
    @import "resources/sass/variables";
    .loaderContainer {
        position: absolute;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 100;
        background-color: #292524;
    }

    .progressbar {
        height: 18px;
        position: absolute;
        left: 50%;
        top: 50%;
        width: 200px;
        background: rgb(28, 25, 23);
        border-radius: 10px;
        margin: -20px 0 0 -100px;
        padding: 2px;
        box-shadow: inset 0 0 #fff, inset 0 0 0 1px #44403c, 0 1px 2px #0000000d;
    }

    .loading-inner {
        transition: all .5s ease;
        height: 7px;
        width: calc(100% - 14px);
        border-radius: 10px;
        background: #00ec66;
        position: absolute;
        margin: 4px 5px;
        display: inline-block;
        animation: load 5s ease infinite;
    }

    @keyframes load{
        0%{
            width: 2%;
        }
        10%{
            width: 10%;
        }
    }
</style>
