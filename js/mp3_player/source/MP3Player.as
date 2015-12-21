/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */
package
{
import com.flash.mp3player.common.CommonModel;
import com.flash.mp3player.common.ConfigEvent;
import com.flash.mp3player.common.ConfigLoader;
import com.flash.mp3player.player.PlayerFacade;
import com.flash.mp3player.player.events.FirstSongStartEvent;
import com.flash.mp3player.player.events.NextPrevEvent;
import com.flash.mp3player.player.events.UpdateEvent;
import com.flash.mp3player.playlist.PlaylistFacade;
import com.flash.mp3player.playlist.events.PlaylistEvent;
import com.flash.mp3player.playlist.events.SongClickEvent;

import flash.display.Sprite;
import flash.display.StageAlign;
import flash.display.StageScaleMode;
import flash.events.Event;
import flash.events.IOErrorEvent;
import flash.external.ExternalInterface;
import flash.net.URLLoader;
import flash.net.URLLoaderDataFormat;
import flash.net.URLRequest;
import flash.net.URLRequestMethod;
import flash.net.URLVariables;

public class MP3Player extends Sprite
{
	//properties
	private var playlistFacade : PlaylistFacade = PlaylistFacade.GetInstance();
	private var playerFacade : PlayerFacade = PlayerFacade.GetInstance();

	private var configLoader : ConfigLoader;

	//constructor
	public function MP3Player()
	{
		this.stage.scaleMode = StageScaleMode.NO_SCALE;
		this.stage.align = StageAlign.TOP_LEFT;

		var configURL : String = "./settings.xml";
		if(this.root.loaderInfo.parameters.configURL != null)
			configURL = this.root.loaderInfo.parameters.configURL;

		CommonModel.GetInst();

		this.playerFacade.Init(this);
		this.playlistFacade.Init(this.playerFacade.GetHeight() - 1, this);

		this.addEventListener(PlaylistEvent.PLAYLIST_LOADED, this.PlaylistHandler);
		this.addEventListener(FirstSongStartEvent.START, this.FirstSongStartHandler);
		this.addEventListener(SongClickEvent.SONG_PLAY, this.PlaySongHandler);
		this.addEventListener(NextPrevEvent.NEXT, this.NextSongHandler);
		this.addEventListener(NextPrevEvent.PREV, this.PrevSongHandler);
		this.addEventListener(UpdateEvent.UPDATE, this.PlaylistUpdateHandler);

		LoadConfig(configURL);

	}

	public function get currentURL() : String
	{
//		var url : String;
//		if(ExternalInterface.available)
//		{
//			return ExternalInterface.call("window.location.href");
//		}
		return stage.loaderInfo.url;
	}

	private function LoadConfig(configURL : String) : void
	{
		this.configLoader = new ConfigLoader();
		this.configLoader.Load(configURL);
		this.configLoader.addEventListener(ConfigEvent.CONFIG_LOADED, this.ConfigLoadedHandler);
	}

	private function ConfigLoadedHandler(e : ConfigEvent) : void
	{
		if(e.result)
		{
			this.playlistFacade.ApplySettings(e.xml);
			this.playerFacade.ApplySettings(e.xml);
		}
		else
		{
			throw new Error("XML is not loaded!");
		}
	}

	private function PlaylistHandler(evt : PlaylistEvent) : void
	{
		this.playerFacade.HideSplaashScreen();
		this.playerFacade.ShowFirstSong(this.playlistFacade.GetFirstSong());
	}

	private function FirstSongStartHandler(evt : FirstSongStartEvent) : void
	{
		this.playlistFacade.SelectFirstSong();
	}

	private function PlaySongHandler(evt : SongClickEvent) : void
	{
		this.playerFacade.PlaySong(evt.songVO);
	}

	private function NextSongHandler(evt : NextPrevEvent) : void
	{
		this.playerFacade.PlaySong(this.playlistFacade.GetNextSong());
	}

	private function PrevSongHandler(evt : NextPrevEvent) : void
	{
		this.playerFacade.PlaySong(this.playlistFacade.GetPrevSong());
	}

	private function PlaylistUpdateHandler(evt : UpdateEvent) : void
	{
		this.playlistFacade.UpdatePlaylist(evt.songVO);
	}
}
}
