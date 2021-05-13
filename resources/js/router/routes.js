function page(path) {
  return () =>
    import(/* webpackChunkName: '' */ `@/pages/${path}`).then(
      m => m.default || m
    );
}

export default [
  { path: '/', name: 'welcome', component: page('Welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/Login.vue') },
  { path: '/register', name: 'register', component: page('auth/Register.vue') },
  {
    path: '/password/reset',
    name: 'password.request',
    component: page('auth/password/Email.vue')
  },
  {
    path: '/password/reset/:token',
    name: 'password.reset',
    component: page('auth/password/Reset.vue')
  },
  {
    path: '/email/verify/:id',
    name: 'verification.verify',
    component: page('auth/verification/Verify.vue')
  },
  {
    path: '/email/resend',
    name: 'verification.resend',
    component: page('auth/verification/Resend.vue')
  },

  { path: '/home', name: 'home', component: page('Home.vue') },
  {
    path: '/settings',
    component: page('settings/Index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      {
        path: 'profile',
        name: 'settings.profile',
        component: page('settings/Profile.vue')
      },
      {
        path: 'password',
        name: 'settings.password',
        component: page('settings/Password.vue')
      }
    ]
  },

  { path: '*', component: page('errors/404.vue') }
];
