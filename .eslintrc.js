module.exports = {
  root: true,
  env: {
    node: true,
    browser: true
  },
  parser: 'vue-eslint-parser',
  parserOptions: {
    parser: '@babel/eslint-parser',
    ecmaVersion: 2018,
    sourceType: 'module'
  },
  extends: [
    'plugin:vue/recommended',
    'standard',
    'prettier',
    'plugin:prettier/recommended'
  ],
  rules: {
    'vue/max-attributes-per-line': 'off',
    'prettier/prettier': 'error',
    'vue/component-name-in-template-casing': ['error', 'PascalCase']
  }
};
