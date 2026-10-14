const path = require('path');
const fs = require('fs');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const stylesDir = path.resolve(__dirname, 'src/css');
const iconFont = './src/icons.font.js';

/**
 * One CSS bundle per index.scss.
 * src/css/index.scss        -> main.min.css
 * src/css/admin/index.scss  -> admin.min.css
 * src/css/blocks/index.scss -> blocks.min.css
 *
 * The root bundle is named mainStyles so it does not collide with main.js.
 */
function getStyleEntries() {
    const entries = {};
    const rootIndex = path.join(stylesDir, 'index.scss');

    if (fs.existsSync(rootIndex)) {
        entries.mainStyles = [rootIndex, iconFont];
    }

    for (const entry of fs.readdirSync(stylesDir, { withFileTypes: true })) {
        if (!entry.isDirectory()) {
            continue;
        }

        const indexFile = path.join(stylesDir, entry.name, 'index.scss');

        if (fs.existsSync(indexFile)) {
            entries[entry.name] = [indexFile, iconFont];
        }
    }

    return entries;
}

const styleEntries = getStyleEntries();

class RemoveEmptyScriptsPlugin {
    apply(compiler) {
        compiler.hooks.thisCompilation.tap('RemoveEmptyScriptsPlugin', (compilation) => {
            compilation.hooks.processAssets.tap(
                {
                    name: 'RemoveEmptyScriptsPlugin',
                    stage: compilation.PROCESS_ASSETS_STAGE_OPTIMIZE_INLINE,
                },
                () => {
                    for (const entryName of Object.keys(styleEntries)) {
                        const assetName = `${entryName}.js`;

                        if (compilation.getAsset(assetName)) {
                            compilation.deleteAsset(assetName);
                        }
                    }
                }
            );
        });
    }
}

module.exports = {
    mode: 'production',
    entry: {
        main: './src/main.js',
        adminScript: './src/js/admin/block-editor.js',
        ...styleEntries,
    },
    output: {
        path: path.resolve(__dirname, 'assets'),
        filename: '[name].js',
        clean: true,
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: { url: false },
                    },
                    'sass-loader',
                ],
            },
            {
                test: /\.font\.js$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false,
                        },
                    },
                    {
                        loader: 'webfonts-loader',
                        options: {
                            // Keep font URLs relative to the extracted CSS file.
                            publicPath: '',
                        },
                    },
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: (pathData) => {
                const name = pathData.chunk.name.replace(/Styles$/, '');
                return `${name}.min.css`;
            },
        }),
        new RemoveEmptyScriptsPlugin(),
    ],
};
