import { openBlock, createElementBlock, createElementVNode, resolveComponent, resolveDirective, withDirectives, normalizeClass, Fragment, renderList, renderSlot, normalizeProps, mergeProps, toDisplayString, withModifiers, createCommentVNode, withKeys, vModelText, createBlock, guardReactiveProps } from 'vue';

var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

function createCommonjsModule(fn, basedir, module) {
	return module = {
	  path: basedir,
	  exports: {},
	  require: function (path, base) {
      return commonjsRequire(path, (base === undefined || base === null) ? module.path : base);
    }
	}, fn(module, module.exports), module.exports;
}

function commonjsRequire () {
	throw new Error('Dynamic requires are not currently supported by @rollup/plugin-commonjs');
}

var vClickOutside_umd = createCommonjsModule(function (module, exports) {
!function(e,n){module.exports=n();}(commonjsGlobal,function(){var e="__v-click-outside",n="undefined"!=typeof window,t="undefined"!=typeof navigator,r=n&&("ontouchstart"in window||t&&navigator.msMaxTouchPoints>0)?["touchstart"]:["click"],i=function(e){var n=e.event,t=e.handler;(0, e.middleware)(n)&&t(n);},a=function(n,t){var a=function(e){var n="function"==typeof e;if(!n&&"object"!=typeof e)throw new Error("v-click-outside: Binding value must be a function or an object");return {handler:n?e:e.handler,middleware:e.middleware||function(e){return e},events:e.events||r,isActive:!(!1===e.isActive),detectIframe:!(!1===e.detectIframe),capture:Boolean(e.capture)}}(t.value),o=a.handler,d=a.middleware,c=a.detectIframe,u=a.capture;if(a.isActive){if(n[e]=a.events.map(function(e){return {event:e,srcTarget:document.documentElement,handler:function(e){return function(e){var n=e.el,t=e.event,r=e.handler,a=e.middleware,o=t.path||t.composedPath&&t.composedPath();(o?o.indexOf(n)<0:!n.contains(t.target))&&i({event:t,handler:r,middleware:a});}({el:n,event:e,handler:o,middleware:d})},capture:u}}),c){var l={event:"blur",srcTarget:window,handler:function(e){return function(e){var n=e.el,t=e.event,r=e.handler,a=e.middleware;setTimeout(function(){var e=document.activeElement;e&&"IFRAME"===e.tagName&&!n.contains(e)&&i({event:t,handler:r,middleware:a});},0);}({el:n,event:e,handler:o,middleware:d})},capture:u};n[e]=[].concat(n[e],[l]);}n[e].forEach(function(t){var r=t.event,i=t.srcTarget,a=t.handler;return setTimeout(function(){n[e]&&i.addEventListener(r,a,u);},0)});}},o=function(n){(n[e]||[]).forEach(function(e){return e.srcTarget.removeEventListener(e.event,e.handler,e.capture)}),delete n[e];},d=n?{beforeMount:a,updated:function(e,n){var t=n.value,r=n.oldValue;JSON.stringify(t)!==JSON.stringify(r)&&(o(e),a(e,{value:t}));},unmounted:o}:{};return {install:function(e){e.directive("click-outside",d);},directive:d}});

});

var vClickOutside = vClickOutside_umd;

const _hoisted_1$1 = {
  class: "v3ti-loader-wrapper"
};

const _hoisted_2$1 = /*#__PURE__*/createElementVNode("div", {
  class: "v3ti-loader"
}, null, -1);

const _hoisted_3$1 = /*#__PURE__*/createElementVNode("span", null, "Loading", -1);

const _hoisted_4$1 = [_hoisted_2$1, _hoisted_3$1];
function render$1(_ctx, _cache) {
  return openBlock(), createElementBlock("div", _hoisted_1$1, _hoisted_4$1);
}

function styleInject(css, ref) {
  if ( ref === void 0 ) ref = {};
  var insertAt = ref.insertAt;

  if (!css || typeof document === 'undefined') { return; }

  var head = document.head || document.getElementsByTagName('head')[0];
  var style = document.createElement('style');
  style.type = 'text/css';

  if (insertAt === 'top') {
    if (head.firstChild) {
      head.insertBefore(style, head.firstChild);
    } else {
      head.appendChild(style);
    }
  } else {
    head.appendChild(style);
  }

  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    style.appendChild(document.createTextNode(css));
  }
}

var css_248z$1 = ".v3ti-loader-wrapper {\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  color: #112B3C;\n}\n.v3ti-loader-wrapper .v3ti-loader {\n  width: 18px;\n  height: 18px;\n  border-radius: 50%;\n  display: inline-block;\n  border-top: 2px solid #112B3C;\n  border-right: 2px solid transparent;\n  box-sizing: border-box;\n  animation: rotation 0.8s linear infinite;\n  margin-right: 8px;\n}\n@keyframes rotation {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}";
styleInject(css_248z$1);

const script$1 = {};
script$1.render = render$1;
var Loading = script$1;

var script = {
  name: "Vue3TagsInput",
  emits: ['update:modelValue', 'update:tags', 'on-limit', 'on-tags-changed', 'on-remove', 'on-error', 'on-focus', 'on-blur', 'on-select', 'on-select-duplicate-tag'],
  props: {
    readOnly: {
      type: Boolean,
      default: false
    },
    modelValue: {
      type: String,
      default: ''
    },
    validate: {
      type: [String, Function, Object],
      default: ""
    },
    addTagOnKeys: {
      type: Array,
      default: function () {
        return [13, // Enter
        188, // Comma ','
        32 // Space
        ];
      }
    },
    placeholder: {
      type: String,
      default: ''
    },
    tags: {
      type: Array,
      default: () => []
    },
    loading: {
      type: Boolean,
      default: false
    },
    limit: {
      type: Number,
      default: -1
    },
    allowDuplicates: {
      type: Boolean,
      default: false
    },
    addTagOnBlur: {
      type: Boolean,
      default: false
    },
    selectItems: {
      type: Array,
      default: () => []
    },
    select: {
      type: Boolean,
      default: false
    },
    duplicateSelectItem: {
      type: Boolean,
      default: true
    },
    uniqueSelectField: {
      type: String,
      default: 'id'
    } // multiple: {
    //   type: Boolean,
    //   default: false
    // },

  },
  components: {
    Loading
  },
  directives: {
    clickOutside: vClickOutside.directive
  },

  data() {
    return {
      isInputActive: false,
      isError: false,
      newTag: '',
      innerTags: [],
      multiple: false
    };
  },

  computed: {
    isLimit() {
      const isLimit = this.limit > 0 && Number(this.limit) === this.innerTags.length;

      if (isLimit) {
        this.$emit('on-limit');
      }

      return isLimit;
    },

    selectedItemsIds() {
      if (!this.duplicateSelectItem) {
        return this.tags.map(o => o[this.uniqueSelectField] || '');
      }

      return [];
    }

  },
  watch: {
    error() {
      this.isError = this.error;
    },

    modelValue: {
      immediate: true,

      handler(value) {
        this.newTag = value;
      }

    },
    tags: {
      deep: true,
      immediate: true,

      handler(tags) {
        this.innerTags = [...tags];
      }

    },

    newTag() {
      if (this.newTag.length > 50) {
        this.$refs.inputTag.className = 'v3ti-new-tag v3ti-new-tag--error';
        this.$refs.inputTag.style.textDecoration = "underline";
      }
    }

  },
  methods: {
    isShot(name) {
      return !!this.$slots[name];
    },

    makeItNormal(event) {
      this.$emit('update:modelValue', event.target.value);
      this.$refs.inputTag.className = 'v3ti-new-tag';
      this.$refs.inputTag.style.textDecoration = "none";
    },

    resetData() {
      this.innerTags = [];
    },

    resetInputValue() {
      this.newTag = '';
      this.$emit('update:modelValue', '');
    },

    setPosition() {
      const el = this.$refs.inputBox;
      const menu = this.$refs.contextMenu;

      if (el && menu) {
        menu.style.display = "block"; // menu.style.position = 'fixed';

        const ELEMENT_HEIGHT = el.clientHeight || 32;
        const BORDER_HEIGHT = 3;
        menu.style.top = ELEMENT_HEIGHT + BORDER_HEIGHT + "px";
      }
    },

    closeContextMenu() {
      if (this.$refs.contextMenu) {
        this.$refs.contextMenu.style = {
          display: 'none'
        };
      }
    },

    handleSelect(tagData) {
      if (this.isShowCheckmark(tagData)) {
        const tags = this.tags.filter(o => tagData.id !== o.id);
        this.$emit('update:tags', tags);
        this.$emit('on-select-duplicate-tag', tagData);
        this.resetInputValue();
      } else {
        this.$emit('on-select', tagData);
      }

      this.$nextTick(() => {
        this.closeContextMenu();
      });
    },

    isShowCheckmark(tag) {
      if (!this.duplicateSelectItem) {
        return this.selectedItemsIds.includes(tag[this.uniqueSelectField]);
      }

      return false;
    },

    focusNewTag() {
      if (this.select && !this.disabled) {
        this.setPosition();
      }

      if (this.readOnly || !this.$el.querySelector(".v3ti-new-tag")) {
        return;
      }

      this.$el.querySelector(".v3ti-new-tag").focus();
    },

    handleInputFocus(event) {
      this.isInputActive = true;
      this.$emit('on-focus', event);
    },

    handleInputBlur(e) {
      this.isInputActive = false;
      this.addNew(e);
      this.$emit('on-blur', e);
    },

    addNew(e) {
      if (this.select) {
        return;
      }

      const keyShouldAddTag = e ? this.addTagOnKeys.indexOf(e.keyCode) !== -1 : true;
      const typeIsNotBlur = e && e.type !== "blur";

      if (!keyShouldAddTag && (typeIsNotBlur || !this.addTagOnBlur) || this.isLimit) {
        return;
      } // this.$nextTick(() => {
      //   if (this.select && this.multiple) {
      //     this.setPosition();
      //   }
      // })


      if (this.newTag && (this.allowDuplicates || this.innerTags.indexOf(this.newTag) === -1) && this.validateIfNeeded(this.newTag) && this.newTag.length <= 50) {
        this.innerTags.push(this.newTag);
        this.resetInputValue();
        this.tagChange();
        e && e.preventDefault();
      } else {
        if (this.validateIfNeeded(this.newTag)) {
          if (this.newTag && this.newTag.length <= 50) {
            this.makeItError(true);
          } else {
            this.makeItError('maxLength');
          }
        } else {
          this.makeItError(false);
        }

        e && e.preventDefault();
      }
    },

    makeItError(isDuplicatedOrMaxLength) {
      this.$refs.inputTag.className = 'v3ti-new-tag v3ti-new-tag--error';
      this.$refs.inputTag.style.textDecoration = "underline";
      this.$emit('on-error', isDuplicatedOrMaxLength);
    },

    validateIfNeeded(tagValue) {
      if (this.validate === "" || this.validate === undefined) {
        return true;
      }

      if (typeof this.validate === "function") {
        return this.validate(tagValue);
      }

      return true;
    },

    removeLastTag() {
      if (this.newTag) {
        return;
      }

      this.innerTags.pop();
      this.tagChange();
    },

    remove(index) {
      this.innerTags.splice(index, 1); // this.$emit('update:tags', this.innerTags);

      this.tagChange();
      this.$emit("on-remove", index); // this.$nextTick(() => {
      //   if (this.select && this.multiple) {
      //     this.setPosition();
      //   }
      // })
    },

    tagChange() {
      this.$emit("on-tags-changed", this.innerTags);
    }

  }
};

const _hoisted_1 = {
  key: 1
};
const _hoisted_2 = ["onClick"];
const _hoisted_3 = ["placeholder"];
const _hoisted_4 = {
  key: 0,
  class: "v3ti-context-menu",
  ref: "contextMenu"
};
const _hoisted_5 = {
  key: 0,
  class: "v3ti-loading"
};
const _hoisted_6 = {
  key: 1,
  class: "v3ti-no-data"
};
const _hoisted_7 = {
  key: 1
};
const _hoisted_8 = {
  key: 2
};
const _hoisted_9 = ["onClick"];
const _hoisted_10 = {
  class: "v3ti-context-item--label"
};
const _hoisted_11 = {
  key: 0,
  class: "v3ti-icon-selected-tag",
  width: "44",
  height: "44",
  viewBox: "0 0 24 24",
  "stroke-width": "1.5",
  fill: "none",
  "stroke-linecap": "round",
  "stroke-linejoin": "round"
};

const _hoisted_12 = /*#__PURE__*/createElementVNode("path", {
  stroke: "none",
  d: "M0 0h24v24H0z"
}, null, -1);

const _hoisted_13 = /*#__PURE__*/createElementVNode("path", {
  d: "M5 12l5 5l10 -10"
}, null, -1);

const _hoisted_14 = [_hoisted_12, _hoisted_13];
function render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_Loading = resolveComponent("Loading");

  const _directive_click_outside = resolveDirective("click-outside");

  return withDirectives((openBlock(), createElementBlock("div", {
    onClick: _cache[6] || (_cache[6] = $event => $options.focusNewTag()),
    class: normalizeClass([{
      'v3ti--focus': $data.isInputActive,
      'v3ti--error': $data.isError
    }, "v3ti"])
  }, [createElementVNode("div", {
    class: normalizeClass(["v3ti-content", {
      'v3ti-content--select': $props.select
    }]),
    ref: "inputBox"
  }, [(openBlock(true), createElementBlock(Fragment, null, renderList($data.innerTags, (tag, index) => {
    return openBlock(), createElementBlock("span", {
      key: index,
      class: "v3ti-tag"
    }, [$options.isShot('item') ? renderSlot(_ctx.$slots, "item", normalizeProps(mergeProps({
      key: 0
    }, {
      name: tag,
      index,
      tag
    }))) : (openBlock(), createElementBlock("span", _hoisted_1, toDisplayString(tag), 1)), !$props.readOnly ? (openBlock(), createElementBlock("a", {
      key: 2,
      onClick: withModifiers($event => $options.remove(index), ["prevent", "stop"]),
      class: "v3ti-remove-tag"
    }, null, 8, _hoisted_2)) : createCommentVNode("", true)]);
  }), 128)), withDirectives(createElementVNode("input", {
    ref: "inputTag",
    placeholder: $props.placeholder,
    "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => $data.newTag = $event),
    onKeydown: [_cache[1] || (_cache[1] = withKeys(withModifiers(function () {
      return $options.removeLastTag && $options.removeLastTag(...arguments);
    }, ["stop"]), ["delete"])), _cache[2] || (_cache[2] = function () {
      return $options.addNew && $options.addNew(...arguments);
    })],
    onBlur: _cache[3] || (_cache[3] = function () {
      return $options.handleInputBlur && $options.handleInputBlur(...arguments);
    }),
    onFocus: _cache[4] || (_cache[4] = function () {
      return $options.handleInputFocus && $options.handleInputFocus(...arguments);
    }),
    onInput: _cache[5] || (_cache[5] = function () {
      return $options.makeItNormal && $options.makeItNormal(...arguments);
    }),
    class: "v3ti-new-tag"
  }, null, 40, _hoisted_3), [[vModelText, $data.newTag]])], 2), $props.select ? (openBlock(), createElementBlock("section", _hoisted_4, [$props.loading ? (openBlock(), createElementBlock("div", _hoisted_5, [$options.isShot('loading') ? renderSlot(_ctx.$slots, "default", {
    key: 0
  }) : (openBlock(), createBlock(_component_Loading, {
    key: 1
  }))])) : createCommentVNode("", true), !$props.loading && $props.selectItems.length === 0 ? (openBlock(), createElementBlock("div", _hoisted_6, [$options.isShot('no-data') ? renderSlot(_ctx.$slots, "no-data", {
    key: 0
  }) : (openBlock(), createElementBlock("span", _hoisted_7, " No data "))])) : createCommentVNode("", true), !$props.loading && $props.selectItems.length > 0 ? (openBlock(), createElementBlock("div", _hoisted_8, [(openBlock(true), createElementBlock(Fragment, null, renderList($props.selectItems, (item, index) => {
    return openBlock(), createElementBlock("div", {
      key: index,
      class: normalizeClass(["v3ti-context-item", {
        'v3ti-context-item--active': $options.isShowCheckmark(item)
      }]),
      onClick: withModifiers($event => $options.handleSelect(item, index), ["stop"])
    }, [createElementVNode("div", _hoisted_10, [renderSlot(_ctx.$slots, "select-item", normalizeProps(guardReactiveProps(item)))]), $options.isShowCheckmark(item) ? (openBlock(), createElementBlock("svg", _hoisted_11, _hoisted_14)) : createCommentVNode("", true)], 10, _hoisted_9);
  }), 128))])) : createCommentVNode("", true)], 512)) : createCommentVNode("", true)], 2)), [[_directive_click_outside, $options.closeContextMenu]]);
}

var css_248z = ".v3ti {\n  border-radius: 5px;\n  min-height: 32px;\n  line-height: 1.4;\n  background-color: #fff;\n  border: 1px solid #9ca3af;\n  cursor: text;\n  text-align: left;\n  -webkit-appearance: textfield;\n  display: flex;\n  flex-wrap: wrap;\n  position: relative;\n}\n.v3ti .v3ti-icon-selected-tag {\n  stroke: #19be6b;\n  width: 1rem;\n  height: 1rem;\n  margin-left: 4px;\n}\n.v3ti--focus {\n  outline: 0;\n  border-color: #000000;\n  box-shadow: 0 0 0 1px #000000;\n}\n.v3ti--error {\n  border-color: #F56C6C;\n}\n.v3ti .v3ti-no-data {\n  color: #d8d8d8;\n  text-align: center;\n  padding: 4px 7px;\n}\n.v3ti .v3ti-loading {\n  padding: 4px 7px;\n  text-align: center;\n}\n.v3ti .v3ti-context-menu {\n  max-height: 150px;\n  min-width: 150px;\n  overflow: auto;\n  display: none;\n  outline: none;\n  position: absolute;\n  top: 0;\n  left: 0;\n  right: 0;\n  margin: 0;\n  padding: 5px 0;\n  background: #ffffff;\n  z-index: 1050;\n  color: #475569;\n  box-shadow: 0 3px 8px 2px rgba(0, 0, 0, 0.1);\n  border-radius: 0 0 6px 6px;\n}\n.v3ti .v3ti-context-menu .v3ti-context-item {\n  padding: 4px 7px;\n  display: flex;\n  align-items: center;\n}\n.v3ti .v3ti-context-menu .v3ti-context-item:hover {\n  background: #e8e8e8;\n  cursor: pointer;\n}\n.v3ti .v3ti-context-menu .v3ti-context-item--label {\n  flex: 1;\n  min-width: 1px;\n}\n.v3ti .v3ti-context-menu .v3ti-context-item--active {\n  color: #317CAF;\n}\n.v3ti .v3ti-content {\n  width: 100%;\n  display: flex;\n  flex-wrap: wrap;\n}\n.v3ti .v3ti-content--select {\n  padding-right: 30px;\n}\n.v3ti .v3ti-tag {\n  display: flex;\n  font-weight: 400;\n  margin: 3px;\n  padding: 0 5px;\n  background: #317CAF;\n  color: #ffffff;\n  height: 27px;\n  border-radius: 5px;\n  align-items: center;\n}\n.v3ti .v3ti-tag .v3ti-remove-tag {\n  color: #ffffff;\n  transition: opacity 0.3s ease;\n  opacity: 0.5;\n  cursor: pointer;\n  padding: 0 5px 0 7px;\n}\n.v3ti .v3ti-tag .v3ti-remove-tag::before {\n  content: \"x\";\n}\n.v3ti .v3ti-tag .v3ti-remove-tag:hover {\n  opacity: 1;\n}\n.v3ti .v3ti-new-tag {\n  background: transparent;\n  border: 0;\n  font-weight: 400;\n  margin: 3px;\n  outline: none;\n  padding: 0 4px;\n  flex: 1;\n  min-width: 60px;\n  height: 27px;\n}\n.v3ti .v3ti-new-tag--error {\n  color: #F56C6C;\n}";
styleInject(css_248z);

script.render = render;

// Import vue component
// IIFE injects install function into component, allowing component
// to be registered via Vue.use() as well as Vue.component(),

var entry_esm = /*#__PURE__*/(() => {
  // Get component instance
  const installable = script; // Attach install function executed by Vue.use()

  installable.install = app => {
    app.component('Vue3TagsInput', installable);
  };

  return installable;
})(); // It's possible to expose named exports when writing components that can
// also be used as directives, etc. - eg. import { RollupDemoDirective } from 'rollup-demo';
// export const RollupDemoDirective = directive;

export { entry_esm as default };
