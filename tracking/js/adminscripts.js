if (typeof(jQuery) != "undefined") {
	if (typeof(jQuery.fn.hoverIntent) == "undefined") {
		(function (b) {
			b.fn.hoverIntent = function (p, r) {
				var g = {sensitivity: 7, interval: 100, timeout: 0};
				g = b.extend(g, r ? {over: p, out: r} : p);
				var a, f, t, v;
				var u = function (c) {
					a = c.pageX;
					f = c.pageY
				};
				var w = function (c, d) {
					d.hoverIntent_t = clearTimeout(d.hoverIntent_t);
					if ((Math.abs(t - a) + Math.abs(v - f)) < g.sensitivity) {
						b(d).unbind("mousemove", u);
						d.hoverIntent_s = 1;
						return g.over.apply(d, [c])
					} else {
						t = a;
						v = f;
						d.hoverIntent_t = setTimeout(function () {
							w(c, d)
						}, g.interval)
					}
				};
				var s = function (c, d) {
					d.hoverIntent_t = clearTimeout(d.hoverIntent_t);
					d.hoverIntent_s = 0;
					return g.out.apply(d, [c])
				};
				var x = function (e) {
					var d = this;
					var c = (e.type == "mouseover" ? e.fromElement : e.toElement) || e.relatedTarget;
					while (c && c != this) {
						try {
							c = c.parentNode
						} catch (e) {
							c = this
						}
					}
					if (c == this) {
						if (b.browser.mozilla) {
							if (e.type == "mouseout") {
								d.mtout = setTimeout(function () {
									q(e, d)
								}, 30)
							} else {
								if (d.mtout) {
									d.mtout = clearTimeout(d.mtout)
								}
							}
						}
						return
					} else {
						if (d.mtout) {
							d.mtout = clearTimeout(d.mtout)
						}
						q(e, d)
					}
				};
				var q = function (e, d) {
					var c = jQuery.extend({}, e);
					if (d.hoverIntent_t) {
						d.hoverIntent_t = clearTimeout(d.hoverIntent_t)
					}
					if (e.type == "mouseover") {
						t = c.pageX;
						v = c.pageY;
						b(d).bind("mousemove", u);
						if (d.hoverIntent_s != 1) {
							d.hoverIntent_t = setTimeout(function () {
								w(c, d)
							}, g.interval)
						}
					} else {
						b(d).unbind("mousemove", u);
						if (d.hoverIntent_s == 1) {
							d.hoverIntent_t = setTimeout(function () {
								s(c, d)
							}, g.timeout)
						}
					}
				};
				return this.mouseover(x).mouseout(x)
			}
		})(jQuery)
	}
	jQuery(document).ready(function (e) {
		var d = e("#wpadminbar"), c, a, b, f = false;
		c = function (g, j) {
			var k = e(j), h = k.attr("tabindex");
			if (h) {
				k.attr("tabindex", "0").attr("tabindex", h)
			}
		};
		a = function (g) {
			d.find("li.menupop").on("click.wp-mobile-hover", function (i) {
				var h = e(this);
				if (!h.hasClass("hover")) {
					i.preventDefault();
					d.find("li.menupop.hover").removeClass("hover");
					h.addClass("hover")
				}
				if (g) {
					e("li.menupop").off("click.wp-mobile-hover");
					f = false
				}
			})
		};
		b = function () {
			var g = /Mobile\/.+Safari/.test(navigator.userAgent) ? "touchstart" : "click";
			e(document.body).on(g + ".wp-mobile-hover", function (h) {
				if (!e(h.target).closest("#wpadminbar").length) {
					d.find("li.menupop.hover").removeClass("hover")
				}
			})
		};
		d.removeClass("nojq").removeClass("nojs");
		if ("ontouchstart" in window) {
			d.on("touchstart", function () {
				a(true);
				f = true
			});
			b()
		} else {
			if (/IEMobile\/[1-9]/.test(navigator.userAgent)) {
				a();
				b()
			}
		}
		d.find("li.menupop").hoverIntent({over: function (g) {
			if (f) {
				return
			}
			e(this).addClass("hover")
		}, out: function (g) {
			if (f) {
				return
			}
			e(this).removeClass("hover")
		}, timeout: 180, sensitivity: 7, interval: 100});
		if (window.location.hash) {
			window.scrollBy(0, -32)
		}
		e("#wp-admin-bar-get-shortlink").click(function (g) {
			g.preventDefault();
			e(this).addClass("selected").children(".shortlink-input").blur(function () {
				e(this).parents("#wp-admin-bar-get-shortlink").removeClass("selected")
			}).focus().select()
		});
		e("#wpadminbar li.menupop > .ab-item").bind("keydown.adminbar",function (i) {
			if (i.which != 13) {
				return
			}
			var h = e(i.target), g = h.closest("ab-sub-wrapper");
			i.stopPropagation();
			i.preventDefault();
			if (!g.length) {
				g = e("#wpadminbar .quicklinks")
			}
			g.find(".menupop").removeClass("hover");
			h.parent().toggleClass("hover");
			h.siblings(".ab-sub-wrapper").find(".ab-item").each(c)
		}).each(c);
		e("#wpadminbar .ab-item").bind("keydown.adminbar", function (h) {
			if (h.which != 27) {
				return
			}
			var g = e(h.target);
			h.stopPropagation();
			h.preventDefault();
			g.closest(".hover").removeClass("hover").children(".ab-item").focus();
			g.siblings(".ab-sub-wrapper").find(".ab-item").each(c)
		});
		e("#wpadminbar").click(function (g) {
			if (g.target.id != "wpadminbar" && g.target.id != "wp-admin-bar-top-secondary") {
				return
			}
			g.preventDefault();
			e("html, body").animate({scrollTop: 0}, "fast")
		});
		e(".screen-reader-shortcut").keydown(function (g) {
			if (13 != g.which) {
				return
			}
			var h = e(this).attr("href");
			if (e.browser.webkit && h && h.charAt(0) == "#") {
				setTimeout(function () {
					e(h).focus()
				}, 100)
			}
		})
	})
} else {
	(function (j, l) {
		var e = function (o, n, d) {
			if (o.addEventListener) {
				o.addEventListener(n, d, false)
			} else {
				if (o.attachEvent) {
					o.attachEvent("on" + n, function () {
						return d.call(o, window.event)
					})
				}
			}
		}, f, g = new RegExp("\\bhover\\b", "g"), b = [], k = new RegExp("\\bselected\\b", "g"), h = function (n) {
			var d = b.length;
			while (d--) {
				if (b[d] && n == b[d][1]) {
					return b[d][0]
				}
			}
			return false
		}, i = function (u) {
			var o, d, r, n, q, s, v = [], p = 0;
			while (u && u != f && u != j) {
				if ("LI" == u.nodeName.toUpperCase()) {
					v[v.length] = u;
					d = h(u);
					if (d) {
						clearTimeout(d)
					}
					u.className = u.className ? (u.className.replace(g, "") + " hover") : "hover";
					n = u
				}
				u = u.parentNode
			}
			if (n && n.parentNode) {
				q = n.parentNode;
				if (q && "UL" == q.nodeName.toUpperCase()) {
					o = q.childNodes.length;
					while (o--) {
						s = q.childNodes[o];
						if (s != n) {
							s.className = s.className ? s.className.replace(k, "") : ""
						}
					}
				}
			}
			o = b.length;
			while (o--) {
				r = false;
				p = v.length;
				while (p--) {
					if (v[p] == b[o][1]) {
						r = true
					}
				}
				if (!r) {
					b[o][1].className = b[o][1].className ? b[o][1].className.replace(g, "") : ""
				}
			}
		}, m = function (d) {
			while (d && d != f && d != j) {
				if ("LI" == d.nodeName.toUpperCase()) {
					(function (n) {
						var o = setTimeout(function () {
							n.className = n.className ? n.className.replace(g, "") : ""
						}, 500);
						b[b.length] = [o, n]
					})(d)
				}
				d = d.parentNode
			}
		}, c = function (q) {
			var o, d, p, n = q.target || q.srcElement;
			while (true) {
				if (!n || n == j || n == f) {
					return
				}
				if (n.id && n.id == "wp-admin-bar-get-shortlink") {
					break
				}
				n = n.parentNode
			}
			if (q.preventDefault) {
				q.preventDefault()
			}
			q.returnValue = false;
			if (-1 == n.className.indexOf("selected")) {
				n.className += " selected"
			}
			for (o = 0, d = n.childNodes.length; o < d; o++) {
				p = n.childNodes[o];
				if (p.className && -1 != p.className.indexOf("shortlink-input")) {
					p.focus();
					p.select();
					p.onblur = function () {
						n.className = n.className ? n.className.replace(k, "") : ""
					};
					break
				}
			}
			return false
		}, a = function (n) {
			var s, q, p, d, r, o;
			if (n.id != "wpadminbar" && n.id != "wp-admin-bar-top-secondary") {
				return
			}
			s = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
			if (s < 1) {
				return
			}
			o = s > 800 ? 130 : 100;
			q = Math.min(12, Math.round(s / o));
			p = s > 800 ? Math.round(s / 30) : Math.round(s / 20);
			d = [];
			r = 0;
			while (s) {
				s -= p;
				if (s < 0) {
					s = 0
				}
				d.push(s);
				setTimeout(function () {
					window.scrollTo(0, d.shift())
				}, r * q);
				r++
			}
		};
		e(l, "load", function () {
			f = j.getElementById("wpadminbar");
			if (j.body && f) {
				j.body.appendChild(f);
				if (f.className) {
					f.className = f.className.replace(/nojs/, "")
				}
				e(f, "mouseover", function (d) {
					i(d.target || d.srcElement)
				});
				e(f, "mouseout", function (d) {
					m(d.target || d.srcElement)
				});
				e(f, "click", c);
				e(f, "click", function (d) {
					a(d.target || d.srcElement)
				})
			}
			if (l.location.hash) {
				l.scrollBy(0, -32)
			}
		})
	})(document, window)
}
;
(function (a) {
	a.fn.hoverIntent = function (k, j) {
		var l = {sensitivity: 7, interval: 100, timeout: 0};
		l = a.extend(l, j ? {over: k, out: j} : k);
		var n, m, h, d;
		var e = function (f) {
			n = f.pageX;
			m = f.pageY
		};
		var c = function (g, f) {
			f.hoverIntent_t = clearTimeout(f.hoverIntent_t);
			if ((Math.abs(h - n) + Math.abs(d - m)) < l.sensitivity) {
				a(f).unbind("mousemove", e);
				f.hoverIntent_s = 1;
				return l.over.apply(f, [g])
			} else {
				h = n;
				d = m;
				f.hoverIntent_t = setTimeout(function () {
					c(g, f)
				}, l.interval)
			}
		};
		var i = function (g, f) {
			f.hoverIntent_t = clearTimeout(f.hoverIntent_t);
			f.hoverIntent_s = 0;
			return l.out.apply(f, [g])
		};
		var b = function (o) {
			var g = jQuery.extend({}, o);
			var f = this;
			if (f.hoverIntent_t) {
				f.hoverIntent_t = clearTimeout(f.hoverIntent_t)
			}
			if (o.type == "mouseenter") {
				h = g.pageX;
				d = g.pageY;
				a(f).bind("mousemove", e);
				if (f.hoverIntent_s != 1) {
					f.hoverIntent_t = setTimeout(function () {
						c(g, f)
					}, l.interval)
				}
			} else {
				a(f).unbind("mousemove", e);
				if (f.hoverIntent_s == 1) {
					f.hoverIntent_t = setTimeout(function () {
						i(g, f)
					}, l.timeout)
				}
			}
		};
		return this.bind("mouseenter", b).bind("mouseleave", b)
	}
})(jQuery);
var showNotice, adminMenu, columns, validateForm, screenMeta;
(function (a) {
	adminMenu = {init: function () {
	}, fold: function () {
	}, restoreMenuState: function () {
	}, toggle: function () {
	}, favorites: function () {
	}};
	columns = {init: function () {
		var b = this;
		a(".hide-column-tog", "#adv-settings").click(function () {
			var d = a(this), c = d.val();
			if (d.prop("checked")) {
				b.checked(c)
			} else {
				b.unchecked(c)
			}
			columns.saveManageColumnsState()
		})
	}, saveManageColumnsState: function () {
		var b = this.hidden();
		a.post(ajaxurl, {action: "hidden-columns", hidden: b, screenoptionnonce: a("#screenoptionnonce").val(), page: pagenow})
	}, checked: function (b) {
		a(".column-" + b).show();
		this.colSpanChange(+1)
	}, unchecked: function (b) {
		a(".column-" + b).hide();
		this.colSpanChange(-1)
	}, hidden: function () {
		return a(".manage-column").filter(":hidden").map(function () {
			return this.id
		}).get().join(",")
	}, useCheckboxesForHidden: function () {
		this.hidden = function () {
			return a(".hide-column-tog").not(":checked").map(function () {
				var b = this.id;
				return b.substring(b, b.length - 5)
			}).get().join(",")
		}
	}, colSpanChange: function (b) {
		var d = a("table").find(".colspanchange"), c;
		if (!d.length) {
			return
		}
		c = parseInt(d.attr("colspan"), 10) + b;
		d.attr("colspan", c.toString())
	}};
	a(document).ready(function () {
		columns.init()
	});
	validateForm = function (b) {
		return !a(b).find(".form-required").filter(function () {
			return a("input:visible", this).val() == ""
		}).addClass("form-invalid").find("input:visible").change(function () {
			a(this).closest(".form-invalid").removeClass("form-invalid")
		}).size()
	};
	showNotice = {warn: function () {
		var b = commonL10n.warnDelete || "";
		if (confirm(b)) {
			return true
		}
		return false
	}, note: function (b) {
		alert(b)
	}};
	screenMeta = {element: null, toggles: null, page: null, init: function () {
		this.element = a("#screen-meta");
		this.toggles = a(".screen-meta-toggle a");
		this.page = a("#wpcontent");
		this.toggles.click(this.toggleEvent)
	}, toggleEvent: function (c) {
		var b = a(this.href.replace(/.+#/, "#"));
		c.preventDefault();
		if (!b.length) {
			return
		}
		if (b.is(":visible")) {
			screenMeta.close(b, a(this))
		} else {
			screenMeta.open(b, a(this))
		}
	}, open: function (b, c) {
		a(".screen-meta-toggle").not(c.parent()).css("visibility", "hidden");
		b.parent().show();
		b.slideDown("fast", function () {
			b.focus();
			c.addClass("screen-meta-active").attr("aria-expanded", true)
		})
	}, close: function (b, c) {
		b.slideUp("fast", function () {
			c.removeClass("screen-meta-active").attr("aria-expanded", false);
			a(".screen-meta-toggle").css("visibility", "");
			b.parent().hide()
		})
	}};
	a(".contextual-help-tabs").delegate("a", "click focus", function (d) {
		var c = a(this), b;
		d.preventDefault();
		if (c.is(".active a")) {
			return false
		}
		a(".contextual-help-tabs .active").removeClass("active");
		c.parent("li").addClass("active");
		b = a(c.attr("href"));
		a(".help-tab-content").not(b).removeClass("active").hide();
		b.addClass("active").show()
	});
	a(document).ready(function () {
		var i = false, d, f, j, h, c = a("#adminmenu"), b, e = a("input.current-page"), g = e.val();
		c.on("click.wp-submenu-head", ".wp-submenu-head", function (k) {
			a(k.target).parent().siblings("a").get(0).click()
		});
		a("#collapse-menu").on("click.collapse-menu", function (l) {
			var k = a(document.body);
			a("#adminmenu div.wp-submenu").css("margin-top", "");
			if (a(window).width() < 900) {
				if (k.hasClass("auto-fold")) {
					k.removeClass("auto-fold");
					setUserSetting("unfold", 1);
					k.removeClass("folded");
					deleteUserSetting("mfold")
				} else {
					k.addClass("auto-fold");
					deleteUserSetting("unfold")
				}
			} else {
				if (k.hasClass("folded")) {
					k.removeClass("folded");
					deleteUserSetting("mfold")
				} else {
					k.addClass("folded");
					setUserSetting("mfold", "f")
				}
			}
		});
		if ("ontouchstart" in window || /IEMobile\/[1-9]/.test(navigator.userAgent)) {
			b = /Mobile\/.+Safari/.test(navigator.userAgent) ? "touchstart" : "click";
			a(document.body).on(b + ".wp-mobile-hover", function (k) {
				if (!a(k.target).closest("#adminmenu").length) {
					c.find("li.wp-has-submenu.opensub").removeClass("opensub")
				}
			});
			c.find("a.wp-has-submenu").on(b + ".wp-mobile-hover", function (m) {
				var l = a(this), k = l.parent();
				if (!k.hasClass("opensub") && (!k.hasClass("wp-menu-open") || k.width() < 40)) {
					m.preventDefault();
					c.find("li.opensub").removeClass("opensub");
					k.addClass("opensub")
				}
			})
		}
		c.find("li.wp-has-submenu").hoverIntent({over: function (s) {
			var u, q, k, r, l = a(this).find(".wp-submenu"), v, n, p, t = parseInt(l.css("top"), 10);
			if (isNaN(t) || t > -5) {
				return
			}
			v = a(this).offset().top;
			n = a(window).scrollTop();
			p = v - n - 30;
			u = v + l.height() + 1;
			q = a("#wpwrap").height();
			k = 60 + u - q;
			r = a(window).height() + n - 15;
			if (r < (u - k)) {
				k = u - r
			}
			if (k > p) {
				k = p
			}
			if (k > 1) {
				l.css("margin-top", "-" + k + "px")
			} else {
				l.css("margin-top", "")
			}
			c.find("li.menu-top").removeClass("opensub");
			a(this).addClass("opensub")
		}, out: function () {
			a(this).removeClass("opensub").find(".wp-submenu").css("margin-top", "")
		}, timeout: 200, sensitivity: 7, interval: 90});
		c.on("focus.adminmenu", ".wp-submenu a",function (k) {
			a(k.target).closest("li.menu-top").addClass("opensub")
		}).on("blur.adminmenu", ".wp-submenu a", function (k) {
			a(k.target).closest("li.menu-top").removeClass("opensub")
		});
		a("div.wrap h2:first").nextAll("div.updated, div.error").addClass("below-h2");
		a("div.updated, div.error").not(".below-h2, .inline").insertAfter(a("div.wrap h2:first"));
		screenMeta.init();
		a("tbody").children().children(".check-column").find(":checkbox").click(function (l) {
			if ("undefined" == l.shiftKey) {
				return true
			}
			if (l.shiftKey) {
				if (!i) {
					return true
				}
				d = a(i).closest("form").find(":checkbox");
				f = d.index(i);
				j = d.index(this);
				h = a(this).prop("checked");
				if (0 < f && 0 < j && f != j) {
					d.slice(f, j).prop("checked", function () {
						if (a(this).closest("tr").is(":visible")) {
							return h
						}
						return false
					})
				}
			}
			i = this;
			var k = a(this).closest("tbody").find(":checkbox").filter(":visible").not(":checked");
			a(this).closest("table").children("thead, tfoot").find(":checkbox").prop("checked", function () {
				return(0 == k.length)
			});
			return true
		});
		a("thead, tfoot").find(".check-column :checkbox").click(function (m) {
			var n = a(this).prop("checked"), l = "undefined" == typeof toggleWithKeyboard ? false : toggleWithKeyboard, k = m.shiftKey || l;
			a(this).closest("table").children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
				if (a(this).closest("tr").is(":hidden")) {
					return false
				}
				if (k) {
					return a(this).prop("checked")
				} else {
					if (n) {
						return true
					}
				}
				return false
			});
			a(this).closest("table").children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
				if (k) {
					return false
				} else {
					if (n) {
						return true
					}
				}
				return false
			})
		});
		a("#default-password-nag-no").click(function () {
			setUserSetting("default_password_nag", "hide");
			a("div.default-password-nag").hide();
			return false
		});
		a("#newcontent").bind("keydown.wpevent_InsertTab", function (p) {
			var m = p.target, r, l, q, k, o;
			if (p.keyCode == 27) {
				a(m).data("tab-out", true);
				return
			}
			if (p.keyCode != 9 || p.ctrlKey || p.altKey || p.shiftKey) {
				return
			}
			if (a(m).data("tab-out")) {
				a(m).data("tab-out", false);
				return
			}
			r = m.selectionStart;
			l = m.selectionEnd;
			q = m.value;
			try {
				this.lastKey = 9
			} catch (n) {
			}
			if (document.selection) {
				m.focus();
				o = document.selection.createRange();
				o.text = "\t"
			} else {
				if (r >= 0) {
					k = this.scrollTop;
					m.value = q.substring(0, r).concat("\t", q.substring(l));
					m.selectionStart = m.selectionEnd = r + 1;
					this.scrollTop = k
				}
			}
			if (p.stopPropagation) {
				p.stopPropagation()
			}
			if (p.preventDefault) {
				p.preventDefault()
			}
		});
		a("#newcontent").bind("blur.wpevent_InsertTab", function (k) {
			if (this.lastKey && 9 == this.lastKey) {
				this.focus()
			}
		});
		if (e.length) {
			e.closest("form").submit(function (k) {
				if (a('select[name="action"]').val() == -1 && a('select[name="action2"]').val() == -1 && e.val() == g) {
					e.val("1")
				}
			})
		}
		a("#contextual-help-link, #show-settings-link").on("focus.scroll-into-view", function (k) {
			if (k.target.scrollIntoView) {
				k.target.scrollIntoView(false)
			}
		});
		(function () {
			var l, k, m = a("form.wp-upload-form");
			if (!m.length) {
				return
			}
			l = m.find('input[type="submit"]');
			k = m.find('input[type="file"]');
			function n() {
				l.prop("disabled", "" === k.map(function () {
					return a(this).val()
				}).get().join(""))
			}

			n();
			k.on("change", n)
		})()
	});
	a(document).bind("wp_CloseOnEscape", function (c, b) {
		if (typeof(b.cb) != "function") {
			return
		}
		if (typeof(b.condition) != "function" || b.condition()) {
			b.cb()
		}
		return true
	})
})(jQuery);
