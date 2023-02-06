<?php

if ( !empty($instance) && !empty($dms_fs) && $instance instanceof DMS && $dms_fs instanceof Freemius ) {
    $platform = $instance->platform;
    $isWpcsPlatformTenant = !empty($platform) && $platform instanceof DMS_Wpcs && DMS_Wpcs::isTenant();
    $save_button_disable = DMS_Helper::disableSaveButton( $platform );
    ?>
    <div id="screen-meta" class="metabox-prefs" style="display: block;">
        <div id="screen-options-wrap">
            <form id="adv-settings" action="<?php 
    echo  admin_url( 'admin-post.php' ) ;
    ?>" method="post">
                <h5><?php 
    _e( 'Available Post Types', $instance->plugin_name );
    ?></h5>
                <em><?php 
    _e( 'Select the Post Types or Custom Taxonomies that should be available for Domain Mapping System.', $instance->plugin_name );
    ?></em>
                <div class="metabox-prefs">
                    <label for="dms_use_page">
                        <input class="hide-postbox-tog" name="dms_use_page" type="checkbox"
                               id="dms_use_page" <?php 
    $opt = get_option( 'dms_use_page' );
    if ( $opt === 'on' ) {
        echo  "checked=\"checked\"" ;
    }
    ?>>
						<?php 
    _e( 'Pages', $instance->plugin_name );
    ?>
                    </label>
                    <label for="dms_use_post">
                        <input class="hide-postbox-tog" name="dms_use_post" type="checkbox"
                               id="dms_use_post" <?php 
    $opt = get_option( 'dms_use_post' );
    if ( $opt === 'on' ) {
        echo  "checked=\"checked\"" ;
    }
    ?>>
						<?php 
    _e( 'Posts', $instance->plugin_name );
    ?>
                    </label>
                    <label for="dms_use_categories">
                        <input class="hide-postbox-tog" name="dms_use_categories" type="checkbox"
                               id="dms_use_categories" <?php 
    $opt = get_option( 'dms_use_categories' );
    if ( $opt === 'on' ) {
        echo  "checked=\"checked\"" ;
    }
    ?>>
						<?php 
    _e( 'Blog Categories', $instance->plugin_name );
    ?>
                    </label>
					<?php 
    $types = DMS::getCustomPostTypes();
    foreach ( $types as $type ) {
        $value = get_option( "dms_use_{$type['name']}" );
        ?>
                        <label for="dms_use_<?php 
        echo  $type['name'] ;
        ?>"><input
                                    class="hide-postbox-tog"
                                    name="dms_use_<?php 
        echo  $type['name'] ;
        ?>"
                                    type="checkbox"
                                    id="dms_use_<?php 
        echo  $type['name'] ;
        ?>" <?php 
        echo  ( $value == 'on' ? 'checked="checked"' : '' ) ;
        ?>><?php 
        echo  $type["label"] ;
        ?>
                        </label>
						<?php 
        
        if ( !empty($type['has_archive']) ) {
            $value = get_option( "dms_use_{$type['name']}_archive" );
            ?>
                            <label for="dms_use_<?php 
            echo  $type['name'] ;
            ?>_archive"><input
                                        class="hide-postbox-tog"
                                        name="dms_use_<?php 
            echo  $type['name'] ;
            ?>_archive"
                                        type="checkbox"
                                        id="dms_use_<?php 
            echo  $type['name'] ;
            ?>_archive" <?php 
            echo  ( $value == 'on' ? 'checked="checked"' : '' ) ;
            ?>><?php 
            echo  $type['label'] ;
            ?>
                                <strong><?php 
            echo  __( 'Archive', $instance->plugin_name ) ;
            ?></strong>
                            </label>
							<?php 
        }
    
    }
    ?>
                    <br class="clear">
                    <p class="submit">
                        <input type="submit" class="button-primary"
                               value="<?php 
    _e( 'Save', $instance->plugin_name );
    ?>"/>
                    </p>
                </div>
                <input name="action" value="save_dms_config" type="hidden">
				<?php 
    wp_nonce_field( 'save_dms_config_action', 'save_dms_config_nonce' );
    ?>
            </form>
        </div>
    </div>
    <div id="screen-meta-links">
        <div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
            <a href="#screen-options-wrap" id="show-settings-link"
               class="show-settings screen-meta-active"><?php 
    _e( 'Configure DMS', $instance->plugin_name );
    ?></a>
        </div>
    </div>
    <!-- Actual Stuff -->
    <div class="wrap">
        <h2><?php 
    _e( 'Domain Mapping System Configuration', $instance->plugin_name );
    ?></h2>
        <div class="updated">
            <p><strong><?php 
    _e( 'Important!', $instance->plugin_name );
    ?></strong></p>
            <p>
				<?php 
    
    if ( empty($platform) ) {
        printf( __( 'This plugin requires configuration with your DNS host and on your server (cPanel, etc). Please see %1$sour documentation%2$s for configuration requirements.', $instance->plugin_name ), '<a target="_blank" href="https://docs.domainmappingsystem.com">', '</a>' );
    } else {
        $platform->printGeneralNotice();
    }
    
    ?>
            </p>
        </div>
        <?php 
    
    if ( $save_button_disable ) {
        ?>
            <div class="updated dms-disabled-delay-note">
                <p><strong><?php 
        _e( 'Important!', $instance->plugin_name );
        ?></strong></p>
                <p><span><?php 
        echo  __( 'It takes up to 3 minutes to process each domain change. Please wait...' ) ;
        ?> <b class="timer"><?php 
        echo  $save_button_disable ;
        ?></b></span></p>
            </div>
        <?php 
    }
    
    // Check and show mdm import
    if ( !empty($instance->mdm_import_instance) && $instance->mdm_import_instance->showImportNote() ) {
        $instance->mdm_import_instance->show();
    }
    // Show admin notices
    DMS_Helper::showSunriseNotices();
    $instance->showAdminNotice();
    
    if ( is_null( $platform ) || !empty($platform) && $platform->showNavigation() ) {
        ?>
            <nav class="nav-tab-wrapper">
                <a href="#domains"
                   class="dms nav-tab <?php 
        echo  ( empty($platform) || $platform->showMappingForm() ? 'nav-tab-active' : '' ) ;
        ?>"><?php 
        _e( 'Domains', $instance->plugin_name );
        ?></a>
                <a href="#hosting-config"
                   class="dms nav-tab <?php 
        echo  ( !empty($platform) && !$platform->showMappingForm() ? 'nav-tab-active' : '' ) ;
        ?>"><?php 
        _e( 'Hosting Config', $instance->plugin_name );
        ?></a>
            </nav>
		<?php 
    }
    
    ?>
        <div id="domains" class="dms-tab-container dms-mapping-container">
			<?php 
    if ( !empty($platform) && !$platform->showMappingForm() ) {
        ?>
                <div class="dms-hide-mapping-overlay"></div>
			<?php 
    }
    ?>
            <form method="post" action="<?php 
    echo  admin_url( 'admin-post.php' ) ;
    ?>">
                <fieldset class="dms">
                    <table class="form-table" id="dms-map">
                        <tr>
                            <th scope="row" colspan="2">
                                <h3><?php 
    _e( 'Domains', $instance->plugin_name );
    ?></h3>
                            </th>
                        </tr>
						<?php 
    
    if ( $isWpcsPlatformTenant ) {
        ?>
                            <tr>
                                <td colspan="2">
                                    <h4 class="dms-root-base-domain-container">
                                        <label for="dms-root-base-domain"><?php 
        _e( 'Main domain:', $instance->plugin_name );
        ?></label>
                                        <input disabled type="text" id="dms-root-base-domain"
                                               class="dms-root-base-domain" value="<?php 
        echo  DMS_Helper::getBaseHost() ;
        ?>">
                                    </h4>
                                </td>
                            </tr>
						<?php 
    }
    
    ?>
                        <tr>
                            <td>
                                <div class="dms-mapping-desc-domain <?php 
    echo  ( $isWpcsPlatformTenant ? 'dms-platform dms-wpcs' : '' ) ;
    ?>">
                                    <strong><?php 
    _e( 'Enter mapped domain:', $instance->plugin_name );
    ?></strong>
                                </div>
                                <div class="dms-mapping-desc-sub-dir <?php 
    echo  ( $isWpcsPlatformTenant ? 'dms-platform dms-wpcs' : '' ) ;
    ?>">
                                    <strong><?php 
    _e( 'Subdirectory:', $instance->plugin_name );
    ?><br>
                                        <small>
											<?php 
    ?>
                                                <a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?> &#8594;</a>
											<?php 
    ?>
                                        </small>
                                    </strong>
                                </div>
                                <div class="dms-mapping-desc-page <?php 
    echo  ( $isWpcsPlatformTenant ? 'dms-platform dms-wpcs' : '' ) ;
    ?>">
                                    <strong><?php 
    _e( 'Select the published content to map for this domain.', $instance->plugin_name );
    ?><br>
										<?php 
    ?>
                                            <small>
												<?php 
    printf( __( 'To create "Microsites" by mapping multiple published resources to a single domain, please %1$sUpgrade%2$s', $instance->plugin_name ), '<a href="' . $dms_fs->get_upgrade_url() . '">', ' &#8594;</a>' );
    ?>
                                            </small>
										<?php 
    ?>
                                    </strong>
                                </div>
                                <div class="dms-mapping-desc-sub-dir <?php 
    echo  ( $isWpcsPlatformTenant ? 'dms-platform dms-wpcs' : '' ) ;
    ?>">
                                    <strong><?php 
    _e( 'Favicon per domain:', $instance->plugin_name );
    ?>
										<?php 
    ?>
                                            <br><small><a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?> &#8594;</a></small>
										<?php 
    ?>
                                    </strong>
                                </div>
                            </td>
                        </tr>
						<?php 
    $options = DMS::getDMSOptions();
    $data = $instance->getData( 1000, 0, false );
    $archive_global_mapping = get_option( 'dms_archive_global_mapping' );
    $woo_shop_global_mapping = get_option( 'dms_woo_shop_global_mapping' );
    $shop_page_association = ( !empty($woo_shop_global_mapping) ? DMS_Helper::getShopPageAssociation() : false );
    
    if ( !empty($data) ) {
        $data_count = count( $data );
        $values = [];
        $possibleMainDomains = [];
        $primary = null;
        $row_key = 0;
        foreach ( $data as $key => $map ) {
            $open_tr = $key == 0 || !empty($data[$key - 1]) && $map->id != $data[$key - 1]->id;
            $close_tr = $key == $data_count - 1 || !empty($data[$key + 1]) && $map->id != $data[$key + 1]->id;
            
            if ( $open_tr ) {
                $domains[] = [
                    'id'   => $map->id,
                    'host' => $map->host . (( !empty($map->path) ? '/' . $map->path : '' )),
                    'main' => $map->main,
                ];
                $values = [];
                $primary = null;
                $row_key++;
                ?>
                                    <tr class="dms-single-mapping" data-index="<?php 
                echo  $row_key ;
                ?>">
                                    <td>
                                    <span class="pre-host">http://</span>
                                    <input type="text" name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][host]"
                                           class="dms regular-text dms-collect-key dms-mapping-host"
                                           value="<?php 
                echo  $map->host ;
                ?>" placeholder="www.example.com"/>
                                    <span class="post-host">/</span>
                                    <span class="sub-dir-cont">
                                    <input type="text"
                                           name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][path]"
                                           <?php 
                echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled' : '' ) ;
                ?>
                                           class="dms medium-text dms-collect-key dms-mapping-path <?php 
                echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'free' : '' ) ;
                ?>"
                                           value="<?php 
                echo  @$map->path ;
                ?>"
                                           placeholder=""/>
                                </span>
                                    <span class="post-host">/</span>
								<?php 
            }
            
            $values[] = $map->value;
            if ( !empty($map->primary) ) {
                $primary = $map->value;
            }
            
            if ( $close_tr ) {
                ?>
                                    <select class="dms"
                                            name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][mappings][values][]"
                                            data-index="<?php 
                echo  $row_key ;
                ?>"
                                            data-placeholder="The choice is yours."
										<?php 
                echo  ( $dms_fs->can_use_premium_code__premium_only() ? 'multiple' : '' ) ;
                ?>>
                                        <option></option>
										<?php 
                foreach ( $options as $key_inner => $optgroup ) {
                    ?>
                                            <optgroup label="<?php 
                    echo  $key_inner ;
                    ?>">
												<?php 
                    foreach ( $optgroup as $option ) {
                        $id = $option['id'];
                        ?>
                                                    <option <?php 
                        echo  ( in_array( $id, $values ) ? 'selected' : '' ) ;
                        ?>
                                                            data-primary="<?php 
                        echo  (int) ($id == $primary) ;
                        ?>"
                                                            class="level-0"
                                                            value="<?php 
                        echo  $option['id'] ;
                        ?>"><?php 
                        echo  $option['title'] ;
                        ?></option>
													<?php 
                    }
                    ?>
                                            </optgroup>
											<?php 
                }
                ?>
                                    </select>
                                    <div class="dms-favicon-uploader">
                                        <img class="dms-favicon-show <?php 
                echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled' : '' ) ;
                ?>"
                                             src="<?php 
                echo  wp_get_attachment_image_url( $map->attachment_id ) ;
                ?>" alt=""
                                             width="25px">
                                        <input type="button" name="upload-btn"
                                               class="<?php 
                echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled' : '' ) ;
                ?> dms-favicon-upload button-secondary upload-btn"
                                               value="<?php 
                echo  __( 'Upload Image', $instance->plugin_name ) ;
                ?>">
                                        <?php 
                ?>
                                    </div>
                                    <button class="dms-delete-row"
                                            title="<?php 
                _e( 'Delete', $instance->plugin_name );
                ?>">
                                        &times;
                                    </button>
									<?php 
                if ( $isWpcsPlatformTenant ) {
                    $platform->drawSetTenantMainDomainButton( $map->id, $save_button_disable );
                }
                ?>
                                    <input type="hidden" class="dms-map-id" name="dms_map[domains][<?php 
                echo  $row_key ;
                ?>][id]"
                                           value="<?php 
                echo  $map->id ;
                ?>"
                                    </td>
                                    </tr>
								<?php 
            }
        
        }
    }
    
    ?>
                        <tr id="dms-add-new-tr">
                            <td>
								<?php 
    
    if ( empty($platform) || !$platform instanceof DMS_Wpcs ) {
        ?>
                                    <strong>
                                        <a class="dms-add-row" href="#"><?php 
        _e( '+ Add Domain Map Entry', $instance->plugin_name );
        ?></a>
                                    </strong>
									<?php 
    }
    
    ?>
                            </td>
                        </tr>
                        <tr id="dms-default-select" style="display: none">
                            <th></th>
                            <td>
                                <select <?php 
    echo  ( $dms_fs->can_use_premium_code__premium_only() ? 'multiple' : '' ) ;
    ?>
                                        data-placeholder="<?php 
    _e( 'The choice is yours.', $instance->plugin_name );
    ?>">
                                    <option></option>
									<?php 
    foreach ( $options as $key => $optgroup ) {
        ?>
                                        <optgroup label="<?php 
        echo  $key ;
        ?>">
											<?php 
        foreach ( $optgroup as $option ) {
            ?>
                                                <option class="level-0" data-primary="0"
                                                        value="<?php 
            echo  $option['id'] ;
            ?>"><?php 
            echo  $option['title'] ;
            ?></option>
												<?php 
        }
        ?>
                                        </optgroup>
										<?php 
    }
    ?>
                                </select>
                            </td>
                        </tr>
                        <tr style="display: none">
                            <td><input type="hidden" id="dms-domains-to-remove" name="dms_map[domains_to_remove]"
                                       value="">
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <h3><?php 
    _e( 'Additional Options', $instance->plugin_name );
    ?></h3>
                <p>
                    <input type="checkbox" <?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                           id="dms_enable_query_strings" name="dms_enable_query_strings" <?php 
    $opt = get_option( 'dms_enable_query_strings' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>/>
					<?php 
    _e( 'Support for query string parameters (e.g. - UTM, etc).', $instance->plugin_name );
    ?>
					<?php 
    ?>
                        <a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>
                            &#8594;</a>
					<?php 
    ?>
                </p>
                <p>
                    <input type="checkbox" <?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                           id="dms_force_site_visitors" name="dms_force_site_visitors" <?php 
    $opt = get_option( 'dms_force_site_visitors' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>/>
					<?php 
    _e( 'Force site visitors to see only mapped domains of a page (e.g. - disallow visitors to see the primary domain of a page).', $instance->plugin_name );
    ?>
					<?php 
    ?>
                        <a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>
                            &#8594;</a>
					<?php 
    ?>
                </p>
                <p>
                    <input type="checkbox" <?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                           id="dms_global_mapping"
                           name="dms_global_mapping" <?php 
    $opt = get_option( 'dms_global_mapping' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>/>
					<?php 
    _e( 'Enable Global Domain Mapping (all pages will be served for your mapped domains).', $instance->plugin_name );
    ?>
                    <span class="dms-main-domain-container">
                    <?php 
    
    if ( !empty($domains) && count( $domains ) > 1 ) {
        ?>
	                    <?php 
        _e( 'Select the domain [+path] to serve for all unmapped pages:', $instance->plugin_name );
        ?>
                        <select name="dms_main_domain"
                                class="dms-main-domain" <?php 
        echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
        ?>>
                                <option value="0"><?php 
        echo  __( 'Select domain', $instance->plugin_name ) ;
        ?></option>
                                <?php 
        foreach ( $domains as $domain ) {
            if ( !empty($domain['host']) && !empty($domain['path']) && !empty($domain['values']) ) {
            }
            ?>
                                        <option value="<?php 
            echo  $domain['host'] ;
            ?>" <?php 
            echo  ( $domain['main'] ? 'selected' : '' ) ;
            ?> ><?php 
            echo  $domain['host'] ;
            ?></option>
                                <?php 
        }
        ?>
                            </select>
                    <?php 
    }
    
    ?>
                </span>
					<?php 
    ?>
                        <a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>
                            &#8594;</a>
					<?php 
    ?>
                </p>
                <p>
                    <input type="checkbox" <?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                           id="dms_archive_global_mapping" name="dms_archive_global_mapping" <?php 
    if ( $archive_global_mapping === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>/>
					<?php 
    _e( 'Global Archive Mapping - All posts within an archive or category automatically map to the specified domain (archive mappings override Global Domain Mapping).', $instance->plugin_name );
    ?>
					<?php 
    ?>
                        <a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>
                            &#8594;</a>
					<?php 
    ?>
                </p>
                <p>
                    <input type="checkbox" <?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                           id="dms_woo_shop_global_mapping" name="dms_woo_shop_global_mapping" <?php 
    $opt = get_option( 'dms_woo_shop_global_mapping' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>/>
					<?php 
    _e( 'Global Product Mapping - When you map a domain to the Shop page, all products on your site will be available through that domain.', $instance->plugin_name );
    ?>
					<?php 
    ?>
                        <a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>
                            &#8594;</a>
					<?php 
    ?>
                </p>
                <p>
                    <input type="checkbox" <?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>
                           id="dms_rewrite_urls_on_mapped_page" name="dms_rewrite_urls_on_mapped_page" <?php 
    $opt = get_option( 'dms_rewrite_urls_on_mapped_page' );
    if ( $opt === 'on' && $dms_fs->can_use_premium_code__premium_only() ) {
        echo  "checked=\"checked\"" ;
    }
    ?>/>
					<?php 
    _e( 'Rewrite all URLs on a mapped domain with:', $instance->plugin_name );
    $rewrite_scenario = get_option( 'dms_rewrite_urls_on_mapped_page_sc' );
    ?>
                    <select name="dms_rewrite_urls_on_mapped_page_sc"
						<?php 
    echo  ( !$dms_fs->can_use_premium_code__premium_only() ? 'disabled=disabled' : '' ) ;
    ?>>
                        <option value="1" <?php 
    echo  ( $rewrite_scenario === '1' && $dms_fs->can_use_premium_code__premium_only() ? 'selected' : '' ) ;
    ?>><?php 
    echo  __( 'Global Rewriting', $instance->plugin_name ) ;
    ?></option>
                        <option value="2" <?php 
    echo  ( $rewrite_scenario === '2' && $dms_fs->can_use_premium_code__premium_only() ? 'selected' : '' ) ;
    ?>><?php 
    echo  __( 'Selective Rewriting', $instance->plugin_name ) ;
    ?></option>
                    </select>
					<?php 
    ?>
                        <a href="<?php 
    echo  $dms_fs->get_upgrade_url() ;
    ?>"><?php 
    _e( 'Upgrade', $instance->plugin_name );
    ?>
                            &#8594;</a>
					<?php 
    ?>
                    <br>
					<?php 
    echo  sprintf(
        __( '%s Warning: %s Global Rewriting may create dead links if you haven’t mapped internally linked pages properly. 
                    Read more in our %s Documentation > %s', $instance->plugin_name ),
        '<strong>',
        '</strong>',
        '<a href="https://docs.domainmappingsystem.com/features/url-rewriting" target="_blank" >',
        '</a>'
    ) ;
    ?>
                </p>
                <p>
                    <input type="checkbox" id="dms_delete_upon_uninstall"
                           name="dms_delete_upon_uninstall" <?php 
    $opt = get_option( 'dms_delete_upon_uninstall' );
    if ( $opt === 'on' ) {
        echo  "checked=\"checked\"" ;
    }
    ?>/>
					<?php 
    _e( 'Delete plugin, data, and settings (full removal) when uninstalling.', $instance->plugin_name );
    ?>
                </p>
                <p class="submit">
                    <input type="submit" class="button-primary"
						<?php 
    echo  ( $save_button_disable ? 'disabled data-disabled_delay="' . $save_button_disable . '"' : '' ) ;
    ?>
                           value="<?php 
    _e( 'Save', $instance->plugin_name );
    ?>"
                           id="dms-submit-config"/>
                </p>
                <input name="action" value="save_dms_mapping" type="hidden">
				<?php 
    wp_nonce_field( 'save_dms_mapping_action', 'save_dms_mapping_nonce' );
    ?>
            </form>
        </div>
		<?php 
    
    if ( is_null( $platform ) || !empty($platform) && $platform->showConfigForm() ) {
        ?>
            <div id="hosting-config" class="dms-tab-container dms-hosting-platform-container">
                <h3><?php 
        _e( 'Allow Domain Mapping System to automatically manage Addon or Alias Domains in your hosting platform by setting up the configuration details below.', $instance->plugin_name );
        ?>
                </h3>
                <p><?php 
        echo  __( 'Detected Hosting Platform', $instance->plugin_name ) ;
        ?></p>
                <div class="dms-platform-name"><?php 
        echo  ( !empty($platform) ? $platform->getName() : __( 'Hosting Platform Not Yet Integrated - Manual Configuration Required', $instance->plugin_name ) ) ;
        ?></div>
                <p><?php 
        echo  sprintf( __( 'We are currently building integrations for multiple hosting platforms. Check currently %s Supported Hosting Platforms %s or contact us at support@domainmappingsystem.com to request yours.', $instance->plugin_name ), '<a target="_blank" href="https://docs.domainmappingsystem.com/faqs/what-hosting-companies-are-supported">', '</a>' ) ;
        ?></p>
				<?php 
        if ( !empty($platform) ) {
            $platform->drawForm();
        }
        ?>
            </div>
		<?php 
    }
    
    if ( $isWpcsPlatformTenant ) {
        $platform->drawSetTenantDomainAsMainForm();
    }
    ?>
    </div>
<?php 
}
