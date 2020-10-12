import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);
import Home from "../components/Home";
import Dashboard from "../components/dashboard/Container";
import managestore from "../components/masterlist/Managestore";
import manageproduct from "../components/masterlist/ManageProduct";
import manageprice from "../components/masterlist/ManagePrice";
import managemeal from "../components/masterlist/ManageMeal";
import manageuser from "../components/masterlist/ManageUser";
import manageqr from "../components/masterlist/ManageQr";
import managerecipe from "../components/masterlist/ManageRecipe";
import meals from "../components/frontendviews/Home";
import login from "../components/authentication/Login";
// import register from "../components/authentication/Register";

const routes = [
    {
        path: "/home",
        component: Home,
        children: [
            {
                component: Dashboard,
                name: "dashboard",
                path: "/dashboard",
                // meta: {authOnly: true}
                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },
            {
                component: Home,
                name: "home",
                path: "/home",

                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },
            {
                component: managestore,
                name: "manage-store",
                path: "/manage-store",
                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },
            {
                component: manageproduct,
                name: "manage-product",
                path: "/manage-product",
                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },
            {
                component: manageprice,
                name: "manage-price",
                path: "/manage-price",
                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },
            {
                component: managemeal,
                name: "manage-meal",
                path: "/manage-meal",
                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },
            {
                component: managerecipe,
                name: "manage-recipe",
                path: "/manage-recipe",
                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },
            {
                component: manageqr,
                name: "manage-qr",
                path: "/manage-qr"
            },
            {
                component: manageuser,
                name: "manage-user",
                path: "/manage-user",
                beforeEnter(to, from, next) {
                    if (isLoggedIn()) {
                        next();
                    } else {
                        next("/login");
                    }
                }
            },

            {
                component: meals,
                name: "meals",
                path: "/meals"
            }
        ]
        // beforeEnter(to, from, next) {
        //     if (!isLoggedIn()) {
        //         next("/");
        //     } else {
        //         next();
        //     }
        // }
    },

    // {
    //     component: login,
    //     name: "login",
    //     path: "/login",
    //     beforeEnter(to, from, next) {
    //         if (!isLoggedIn()) {
    //             next();
    //         } else {
    //             next("/home");
    //         }
    //     }
    // },
    {
        component: login,
        name: "login",
        path: "/",
        beforeEnter(to, from, next) {
            if (!isLoggedIn()) {
                next();
            } else {
                next("/home");
            }
        }
    }

    // {
    //     component: register,
    //     name: "register",
    //     path: "/register",
    //     beforeEnter(to, from, next){
    //         if (!isLoggedIn()) {
    //             next();
    //         }else{
    //             next('/login');
    //         }
    //     }
    // },
];

export default new Router({
    mode: "history",
    routes: routes,
    linkActiveClass: "active"
});

function isLoggedIn() {
    return localStorage.getItem("auth");
}
