const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

// Plugin to remove empty JS files generated from CSS-only entries
class RemoveEmptyJsPlugin {
    apply(compiler) {
        compiler.hooks.thisCompilation.tap('RemoveEmptyJsPlugin', (compilation) => {
            compilation.hooks.processAssets.tap(
                {
                    name: 'RemoveEmptyJsPlugin',
                    stage: compilation.PROCESS_ASSETS_STAGE_OPTIMIZE_INLINE,
                },
                (assets) => {
                    const cssOnlyEntries = ['mainStyles', 'blocks', 'adminStyles'];

                    cssOnlyEntries.forEach(entryName => {
                        const assetName = `${entryName}.js`;
                        if (assets[assetName]) {
                            compilation.deleteAsset(assetName);
                        }
                    });
                }
            );
        });
    }
}

module.exports = {
    mode: 'production',
    
    entry: {
        main: './src/main.js',
        mainStyles: './src/css/index.scss',
        // blocks: './src/css/blocks/index.scss',
        // adminStyles: './src/css/admin/index.scss',
        adminScript: './src/js/admin/block-editor.js',
    },

    output: {
        path: path.resolve(__dirname, 'assets'),
        filename: '[name].js',
        clean: true,
    },

    module: {
        rules: [
            {
                // Compilation SCSS vers CSS
                test: /\.s[ac]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: { url: false }
                    },
                    'sass-loader'
                ],
            }
        ]
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: (pathData) => {
                // Rename mainStyles to main.min.css
                if (pathData.chunk.name === 'mainStyles') {
                    return 'main.min.css';
                }
                return '[name].min.css';
            },
        }),
        new RemoveEmptyJsPlugin()
    ],
};
