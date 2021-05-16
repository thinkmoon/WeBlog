import Vue from 'vue'
import Marked from "marked";
let renderMd = new Marked.Renderer();
Marked.setOptions({
    renderer: renderMd,
    gfm: true,
    tables: true,
    breaks: false,
    pedantic: false,
    sanitize: false,
    smartLists: true,
    smartypants: false,
});
export const marked =  Marked