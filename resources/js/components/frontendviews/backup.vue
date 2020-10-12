<template>
    <div>
        <template>
            <v-carousel hide-delimiters>
                <v-carousel-item
                    v-for="(item, i) in items"
                    :key="i"
                    :src="item.src"
                ></v-carousel-item>
            </v-carousel>
        </template>
    </div>
</template>
<script>
export default {
    data() {
        return {
            items: [
                {
                    src:
                        "https://panlasangpinoy.com/wp-content/uploads/2017/03/Sinigang-na-Baboy-with-Gabi-Panlasang-Pinoy.jpg"
                },
                {
                    src: "https://cdn.vuetifyjs.com/images/carousel/sky.jpg"
                },
                {
                    src: "https://cdn.vuetifyjs.com/images/carousel/bird.jpg"
                },
                {
                    src: "https://cdn.vuetifyjs.com/images/carousel/planet.jpg"
                }
            ]
        };
    }
};
</script>
